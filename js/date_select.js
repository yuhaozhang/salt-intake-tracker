//**********************************************************
//�����������˵����Զ�������/��/��
//**********************************************************
function YYYYMMDDstart()   
{   
	   MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];   

	   //�ȸ�������������   
	   var y  = new Date().getFullYear();   
	   for (var i = (y-40); i < (y+1); i++) //�Խ���Ϊ׼��ǰ30�꣬��30��   
			   document.getElementsByName('year')[0].options.add(new Option(" "+ i +" ��", i));   

	   //���·ݵ�������   
	   for (var i = 1; i < 13; i++)   
			   document.getElementsByName('month')[0].options.add(new Option(" " + i + " ��", i));   

	   document.getElementsByName('year')[0].value = y;   
	   document.getElementsByName('month')[0].value = new Date().getMonth() + 1;   
	   var n = MonHead[new Date().getMonth()];   
	   if (new Date().getMonth() ==1 && IsPinYear(YYYYvalue)) n++;   
			writeDay(n); //������������Author:meizz   
	   document.getElementsByName('day')[0].value = new Date().getDate();   
}   
if(document.attachEvent)   
   window.attachEvent("onload", YYYYMMDDstart);   
else   
   window.addEventListener('load', YYYYMMDDstart, false);   
function YYYYDD(str) //�귢���仯ʱ���ڷ����仯(��Ҫ���ж���ƽ��)   
{   
	   var MMvalue = document.getElementsByName('month')[0].options[document.getElementsByName('month')[0].selectedIndex].value;   
	   if (MMvalue == ""){ var e = document.getElementsByName('day')[0]; optionsClear(e); return;}   
	   var n = MonHead[MMvalue - 1];   
	   if (MMvalue ==2 && IsPinYear(str)) n++;   
			writeDay(n)   
}   
function MMDD(str)   //�·����仯ʱ��������   
{   
	var YYYYvalue = document.getElementsByName('year')[0].options[document.getElementsByName('year')[0].selectedIndex].value;   
	if (YYYYvalue == ""){ var e = document.getElementsByName('day')[0]; optionsClear(e); return;}   
	var n = MonHead[str - 1];   
	if (str ==2 && IsPinYear(YYYYvalue)) n++;   
   writeDay(n)   
}   
function writeDay(n)   //������д���ڵ�������   
{   
	   var e = document.getElementsByName('day')[0]; optionsClear(e);   
	   for (var i=1; i<(n+1); i++)   
			e.options.add(new Option(" "+ i + " ��", i));   
}   
function IsPinYear(year)//�ж��Ƿ���ƽ��   
{     return(0 == year%4 && (year%100 !=0 || year%400 == 0));}   
function optionsClear(e)   
{   
	e.options.length = 0;   
}   