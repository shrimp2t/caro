<?php

/**
 * @author shrimp2t
 * @packet 
 * @time 2010-7-7 8:10
 * contact : shrimp2t@gmail.com
 * site : http://shrimp2t.com
 */



class db{
     // connect to server.     
     private	$connection		=	false;
     // number of records
     public $num_rows;
     
     public $query;
     public $error;
     public $insert_id;
     public $fields;
     public $pri_key;
   //  public $error;
     
    
     public function __construct($host, $user=0, $pass=0, $db=0){
		$data = $host;
		if(is_array($data)){
			$host = $data[0];
			$user = $data[1];
			$pass = $data[2];
			$db   = $data[3];
		}
			$this->connection = mysql_connect($host, $user, $pass) or die(mysql_error());
			mysql_select_db($db,$this->connection ) or die(mysql_error());
            mysql_query("SET NAMES utf8");  
     }
     
     public function connect(){
        
         
     } 
     
     
     function close(){
          @mysql_close($this->connection);
     }
     
     function tb_prefix($stringsql){
        return str_replace('#__',TABLE_PREFIX,$stringsql);
     }
     
     // get last insert id
     function get_insert_id(){
     	return mysql_insert_id();
     }
     
     function query($stringsql){
          $stringsql=self::tb_prefix($stringsql);
          $this->query  =  $stringsql;
          if($q=@mysql_query($stringsql,$this->connection)){
            $this->error=null;
            return $q;
          }else{
            $this->error=mysql_error();
            return $q;
          }
          
     }
     
     function get_colums($table,$get_pri = false){
        
        if(empty($this->fields[$table])){
             $sql=" SHOW COLUMNS FROM $table ";
          //  echo $tool."\n";
            $f_results  = mysql_query($sql,$this->connection);
            //$fields=array();
           // $this->fields = array();
            while($field=@mysql_fetch_object($f_results)){
              
              if($field->Key!='PRI') {
                    $this->fields[$table][] = $field->Field;
                }else{
                    
                    $this->pri_key[$table] = $field->Field;
                    
                    if($get_pri){
                        $this->fields[$table][] = $field->Field;
                    }
                }  
                    
            }
        }
      
        
        return $this->fields[$table] ;
     }
     
     /**
      * Th�m _____1 record v�o table
      * $data c� c� th? l� 1  m?n d?ng array('cot1'=>'value1','cot2'=>'value2',.....)
      */ 
     function insert($table,$data){
         /*
         INSERT INTO `nv` (`manv`, `hodem`, `ten`, `maphong`, `luong`) 
                    VALUES (NULL, 'dsa', 'dsa', '1', '1');
         */ 
         
         $table_cols =self::get_colums($table) ;
         
         $sql=null;
         $n=count($data);
         //n?u m?ng data c� nhi?u hon ho?c = 1 ph�n t?
         if($n!=0):
         
           $cols=null; /// t�n c�c c?t trong b?ng (l� 1 m?ng)
           $values =null; // gi� tr? c?a c�c c?t tuong ?ng   (m?ng)  ;

                    foreach($data as $k => $v):
                        if(in_array($k,$table_cols)){
                             $cols[]="`$k`";
                             $values[]=self::filter($v,'string');  
                        }
                               
                    endforeach;
                    
              
              if(count($cols)==count($values) and !empty($cols)):
                     $sql=" INSERT INTO $table (".join(',', $cols).') VALUES ('.join(', ',$values).')';
              else:
                    return false;
              endif;  
              
         else:
           return false;
         endif;
         
        // echo var_dump($data).'<br/>';
       // echo $sql."<br/><hr/>";
        return  self::query($sql);
          
     }
     
     function num_rows($sql){
         $query=self::query($sql);
        $this->num_rows=mysql_num_rows($query);
        
        return  $this->num_rows;
     }
     
     function get_row($sql,$type_result='object'){
          
          $query=self::query($sql);
          $this->num_rows=@mysql_num_rows($query);
          if($this->num_rows>0){
                    switch(trim(strtolower($type_result))){
                         case 'array':
                                return @mysql_fetch_array($query);
                         break;  
                         case 'assoc':
                               return @mysql_fetch_assoc($query);
                         break; 
                         case 'row':
                                return @mysql_fetch_row($query);
                         break;
                         default: 
                            return @mysql_fetch_object($query);
                    }
          }
          
         return ; 
     }
     
    
     function get_rows($sql,$type_result='object'){
          $return=array();
          $query=self::query($sql);
          $this->num_rows=mysql_num_rows($query);
          if($this->num_rows>0){
                    switch(trim(strtolower($type_result))){
                         case 'array':
                                while($re=@mysql_fetch_array($query)){
                                          $return[]=$re;
                                }
                         break;  
                         case 'assoc':
                                while($re=@mysql_fetch_assoc($query)){
                                       $return[]=$re;   
                                }
                         break; 
                         case 'row':
                                while($re=@mysql_fetch_row($query)){
                                          $return[]=$re;
                                }
                         break;
                         default: 
                            while($re=@mysql_fetch_object($query)){
                                            $return[]=$re;
                             }
                    }
          }
          if($one){
           
          	return $return[0];
          }
         return $return; 
     }
     
     function update($table,$data,$conditions){
          
          
          $table_cols =self::get_colums($table) ;
          
          $val=array();
          if(is_array($data)){
                    
                    foreach($data as $k=>$v){
                        if(in_array($k,$table_cols)) {
                            $v=self::filter($v,'string');
                            $val[]=" `$k` = $v ";
                        } 
                    }
          }
          
          
          
          if(!count($val)) 
        //  echo 'no';
          return false;
          
          else{
               $sql="UPDATE $table SET".join(', ',$val).self::conditions($conditions)  ;  
             //  echo $sql;
          }
          
         // echo $sql."<br/>";
         return self::query($sql); 
     }
    
     function conditions($conditions){
          $where=null;
           if(is_string($conditions)):
                    $where.=$conditions;
           elseif(is_array($conditions)):
                    $wheres=false;
                    $ins="";
                    $n=($conditions)>1;
                    if($n>1):
                           foreach($conditions as $key=>$value) :
                           
                              $value= self::filter($value,'string');
                              $wheres[]=" $key= $value ";
                              
                           endforeach; 
                    elseif($n==1):
                       
                        foreach($conditions as $key=>$value) :
                              $wheres=$key;
                               
                              if(is_array($value)):
                                  $value=self::filter($value,'array');
                                  //
                                  $ins=" IN (".join(', ',$value).")";
                               else:
                                        
                                        $ins=' = '.self::filter($value,'string');
                                 
                              endif;
                           endforeach; 
                           
                    endif;
                    if(is_array($wheres)){
                              $where= join(' AND ', $wheres);
                    }else{
                           $where=$wheres.$ins;   
                    }
          
           endif;
           return " WHERE ".$where;
          
     }
     
     
     function delete($table, $conditions){

          $sql="DELETE FROM $table ".self::conditions($conditions);
          
          return $this->query($sql);
     }   
     
     
     function filter($value,$type=false){
          
          switch(trim(strtolower($type))){
            case 'string':
                $value="'".mysql_real_escape_string($value)."'";
            break;
            case 'array':
                foreach ($value as $k=>$v){
                    $value[$k]="'".mysql_real_escape_string($value)."'";
                }
            break;
            
          }
          
          return $value;
     }
     function error($result){
      //  return mysql_error($result);
     }
             
}
