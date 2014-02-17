
var Ready= false;

boardSize=20;
userSq= 1;
machSq=-1;
blinkSq="b-1";
myTurn=false;
winningMove=9999999;
openFour   =8888888;
twoThrees  =7777777;

image_path ='images/';
if (document.images) {
    uImg=new Image(16,16); uImg.src=image_path+'s'+userSq+'.png';
    mImg=new Image(16,16); mImg.src=image_path+'/s'+machSq+'.png';
    bImg=new Image(16,16); bImg.src=image_path+'images/s0.png';
}

f=new Array();
s=new Array();
q=new Array();
for (i=0;i<20;i++) {
    f[i]=new Array();
    s[i]=new Array();
    q[i]=new Array();
    for (j=0;j<20;j++) {
        f[i][j]=0;
        s[i][j]=0;
        q[i][j]=0;
    }
}

iLastUserMove=0;
jLastUserMove=0;

function clk(iMove,jMove) {
    if (myTurn) return;
    if (f[iMove][jMove]!=0) {alert('This square is not empty! Please choose another.'); return; }
    f[iMove][jMove]=userSq;
    drawSquare(iMove,jMove,userSq);
    myTurn=true;
    iLastUserMove=iMove;
    jLastUserMove=jMove;

    dly=(document.images)?10:boardSize*30;

    console.debug(dly);

    if (winningPos(iMove,jMove,userSq)==winningMove) setTimeout("alert('You won!');",dly);
    else setTimeout("machineMove(iLastUserMove,jLastUserMove);",dly);
}

// may tu dong di
function machineMove(iUser,jUser) {
    maxS=evaluatePos(s,userSq);
    maxQ=evaluatePos(q,machSq);

    // alert ('maxS='+maxS+', maxQ='+maxQ);

    if (maxQ>=maxS) {
        maxS=-1;
        for (i=0;i<boardSize;i++) {
            for (j=0;j<boardSize;j++) {
                if (q[i][j]==maxQ && s[i][j]>maxS) {
                    maxS=s[i][j];
                    iMach=i;
                    jMach=j;
                }
            }
        }
    }

    else {
        maxQ=-1;
        for (i=0;i<boardSize;i++) {
            for (j=0;j<boardSize;j++) {
                if (s[i][j]==maxS && q[i][j]>maxQ) {
                    maxQ=q[i][j];
                    iMach=i;
                    jMach=j;
                }
            }
        }
    }

    f[iMach][jMach]=machSq;
    if (document.images) {
        drawSquare(iMach,jMach,blinkSq);
       // setTimeout("drawSquare(iMach,jMach,machSq);",900);
    }
    else {
        drawSquare(iMach,jMach,machSq);
    }
    if (winningPos(iMach,jMach,machSq)==winningMove) setTimeout("alert('I won!')",900);
    else myTurn=false;
}


function hasNeighbors(i,j) {
    if (j>0 && f[i][j-1]!=0) return 1;
    if (j+1<boardSize && f[i][j+1]!=0) return 1;
    if (i>0) {
        if (f[i-1][j]!=0) return 1;
        if (j>0 && f[i-1][j-1]!=0) return 1;
        if (j+1<boardSize && f[i-1][j+1]!=0) return 1;
    }
    if (i+1<boardSize) {
        if (f[i+1][j]!=0) return 1;
        if (j>0 && f[i+1][j-1]!=0) return 1;
        if (j+1<boardSize && f[i+1][j+1]!=0) return 1;
    }
    return 0;
}

w=new Array(0,20,17,15.4,14,10);
nPos=new Array();
dirA=new Array();


function displayWin(track){
    console.debug(track);
    if(track.length==0){
        return;
    }
    for(k=0; k<track.length; k++ ){
       ij=  track[k];

       $('.ij-'+ij).addClass('track-win');
    }

}


// kiem tra chien thang hay ko ?
function winningPos(i,j,mySq) {

    track = new Array();
    track[0] = i+'-'+j;

    test3=0;

    L=1;
    m=1;
    while (j+m<boardSize  && f[i][j+m]==mySq) {
        track[L] = i+'-'+(j+m);
        L++; m++;

    }

    m1=m;
    m=1;

    while (j-m>=0 && f[i][j-m]==mySq) {
        track[L] = i+'-'+(j-m);
        L++; m++;

    }

    m2=m;
    // nếu nhiều hơn 4 điểm liền nhau
    if (L>4) {
        displayWin(track);

        return winningMove;
    }

    side1=(j+m1<boardSize && f[i][j+m1]==0);
    side2=(j-m2>=0 && f[i][j-m2]==0);

    if (L==4 && (side1 || side2)) test3++;
    if (side1 && side2) {
        if (L==4) return openFour;
        if (L==3) test3++;
    }

    L=1;
    m=1;
    while (i+m<boardSize  && f[i+m][j]==mySq) {
        track[L]=(i+m)+'-'+(j);
        L++; m++;

    }
    m1=m;
    m=1;
    while (i-m>=0 && f[i-m][j]==mySq) {
        track[L]=(i-m)+'-'+j;
        L++; m++;

    }

    m2=m;
    // nếu nhiều hơn 4 điểm liền nhau
    if (L>4) {
        displayWin(track);
        return winningMove;
    }

    side1=(i+m1<boardSize && f[i+m1][j]==0);
    side2=(i-m2>=0 && f[i-m2][j]==0);
    if (L==4 && (side1 || side2)) test3++;
    if (side1 && side2) {
        if (L==4) return openFour;
        if (L==3) test3++;
    }
    if (test3==2) return twoThrees;

    L=1;
    m=1;
    while (i+m<boardSize && j+m<boardSize && f[i+m][j+m]==mySq) {
        track[L]=(i-m)+'-'+(j+m);
        L++; m++;

    }
    m1=m;
    m=1;

    while (i-m>=0 && j-m>=0 && f[i-m][j-m]==mySq) {
        track[L]=(i-m)+'-'+(j-m);
        L++; m++;


    }
    m2=m;

    // nếu nhiều hơn 4 điểm liền nhau
    if (L>4) {
        displayWin(track);
        return winningMove;
    }


    side1=(i+m1<boardSize && j+m1<boardSize && f[i+m1][j+m1]==0);

    side2=(i-m2>=0 && j-m2>=0 && f[i-m2][j-m2]==0);
    if (L==4 && (side1 || side2)) test3++;
    if (side1 && side2) {
        if (L==4) return openFour;
        if (L==3) test3++;
    }

    if (test3==2) return twoThrees;

    L=1;
    m=1;
    while (i+m<boardSize  && j-m>=0 && f[i+m][j-m]==mySq) {
        track[L]=(i+m)+'-'+(j-m);
        L++; m++;

    }

    m1=m;
    m=1;
    while (i-m>=0 && j+m<boardSize && f[i-m][j+m]==mySq) {
        track[L]=(i-m)+'-'+(j+m);
        L++; m++;

    }
    m2=m;

    // nếu nhiều hơn 4 điểm liền nhau
    if (L>4) {
        displayWin(track);
        return winningMove;
    }

    side1=(i+m1<boardSize && j-m1>=0 && f[i+m1][j-m1]==0);
    side2=(i-m2>=0 && j+m2<boardSize && f[i-m2][j+m2]==0);
    if (L==4 && (side1 || side2)) test3++;
    if (side1 && side2) {
        if (L==4) return openFour;
        if (L==3) test3++;
    }

    if (test3==2) return twoThrees;

    return -1;
}

// danh gia nuoc di
function evaluatePos(a,mySq) {
    maxA=-1;
    for (i=0;i<boardSize;i++) {
        for (j=0;j<boardSize;j++) {

            // Compute "value" a[i][j] of the (i,j) move

            if (f[i][j]!=0) {a[i][j]=-1; continue;}
            if (hasNeighbors(i,j)==0) {a[i][j]=-1; continue;}
            wp=winningPos(i,j,mySq);
            if (wp==winningMove) {a[i][j]=winningMove; return winningMove;}
            if (wp>=twoThrees)   {a[i][j]=wp; if (maxA<wp) maxA=wp; continue;}

            minM=i-4; if (minM<0) minM=0;
            minN=j-4; if (minN<0) minN=0;
            maxM=i+5; if (maxM>boardSize) maxM=boardSize;
            maxN=j+5; if (maxN>boardSize) maxN=boardSize;

            nPos[1]=1; A1=0;
            m=1; while (j+m<maxN  && f[i][j+m]!=-mySq) {nPos[1]++; A1+=w[m]*f[i][j+m]; m++}
            if (j+m>=boardSize || f[i][j+m]==-mySq) A1-=(f[i][j+m-1]==mySq)?(w[5]*mySq):0;
            m=1; while (j-m>=minN && f[i][j-m]!=-mySq) {nPos[1]++; A1+=w[m]*f[i][j-m]; m++}
            if (j-m<0 || f[i][j-m]==-mySq) A1-=(f[i][j-m+1]==mySq)?(w[5]*mySq):0;

            nPos[2]=1; A2=0;
            m=1; while (i+m<maxM  && f[i+m][j]!=-mySq) {nPos[2]++; A2+=w[m]*f[i+m][j]; m++}
            if (i+m>=boardSize || f[i+m][j]==-mySq) A2-=(f[i+m-1][j]==mySq)?(w[5]*mySq):0;
            m=1; while (i-m>=minM && f[i-m][j]!=-mySq) {nPos[2]++; A2+=w[m]*f[i-m][j]; m++}
            if (i-m<0 || f[i-m][j]==-mySq) A2-=(f[i-m+1][j]==mySq)?(w[5]*mySq):0;

            nPos[3]=1; A3=0;
            m=1; while (i+m<maxM  && j+m<maxN  && f[i+m][j+m]!=-mySq) {nPos[3]++; A3+=w[m]*f[i+m][j+m]; m++}
            if (i+m>=boardSize || j+m>=boardSize || f[i+m][j+m]==-mySq) A3-=(f[i+m-1][j+m-1]==mySq)?(w[5]*mySq):0;
            m=1; while (i-m>=minM && j-m>=minN && f[i-m][j-m]!=-mySq) {nPos[3]++; A3+=w[m]*f[i-m][j-m]; m++}
            if (i-m<0 || j-m<0 || f[i-m][j-m]==-mySq) A3-=(f[i-m+1][j-m+1]==mySq)?(w[5]*mySq):0;

            nPos[4]=1; A4=0;
            m=1; while (i+m<maxM  && j-m>=minN && f[i+m][j-m]!=-mySq) {nPos[4]++; A4+=w[m]*f[i+m][j-m]; m++;}
            if (i+m>=boardSize || j-m<0 || f[i+m][j-m]==-mySq) A4-=(f[i+m-1][j-m+1]==mySq)?(w[5]*mySq):0;
            m=1; while (i-m>=minM && j+m<maxN  && f[i-m][j+m]!=-mySq) {nPos[4]++; A4+=w[m]*f[i-m][j+m]; m++;}
            if (i-m<0 || j+m>=boardSize || f[i-m][j+m]==-mySq) A4-=(f[i-m+1][j+m-1]==mySq)?(w[5]*mySq):0;

            dirA[1] = (nPos[1]>4) ? A1*A1 : 0;
            dirA[2] = (nPos[2]>4) ? A2*A2 : 0;
            dirA[3] = (nPos[3]>4) ? A3*A3 : 0;
            dirA[4] = (nPos[4]>4) ? A4*A4 : 0;

            A1=0; A2=0;
            for (k=1;k<5;k++) {
                if (dirA[k]>=A1) {A2=A1; A1=dirA[k]}
            }
            thisA=A1+A2;

            a[i][j]=thisA;
            if (thisA>maxA) {
                maxA=thisA;
            }
        }
    }
    return maxA;
}



// ve hinh x hoac o

function drawSquare(par1,par2,par3) {
   // if (document.images) {
      //  eval('self.f1.document.s'+par1+'_'+par2+'.src="images/s'+par3+'.gif"');
        $('#s'+par1+'_'+par2).attr('src','images/s'+par3+'.png');
   // }
   // else setTimeout("writeBoard()",50);
}


buf='';

function writeBoard () {

    buf='';
    for (i=0;i<boardSize;i++) {
        for (j=0;j<boardSize;j++) {
           // buf+='\n><a href="#s" onClick="top.clk('+i+','+j+');if(top.ie4)this.blur();return false;" ><img name="s'+i+'_'+j+'" src="images/s'+f[i][j]+'.gif" width=16 height=16 border=0></a';
            buf+= '<a class="i-'+i+' j-'+j+'  ij-'+i+'-'+j+'" href="#s"  onclick="clk('+i+','+j+');return false;" ><img id="s'+i+'_'+j+'" name="s'+i+'_'+j+'" src="'+image_path+'s'+f[i][j]+'.png"></a> ';

        }

        buf+='<div class="clear"></div>';

    }

    $('#caro-canvas').html(buf);

}

function resetGame() {
   // if (!top.f1.document) return;

    for (i=0;i<20;i++) {
        for (j=0;j<20;j++) {
            f[i][j]=0;
        }
    }
    if (document.images) {
       // if (!top.f1.document.s9_9) return;

        for (i=0;i<boardSize;i++) {
            for (j=0;j<boardSize;j++) {
              //  eval('self.f1.document.s'+i+'_'+j+'.src=bImg.src');
            }
        }
    }
    else writeBoard();
    myTurn=false;

}

function init() {
    writeBoard();
    resetGame();
}


$(document).ready(function(){
    init();
});

