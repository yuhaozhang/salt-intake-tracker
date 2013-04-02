//**********************************************************
//用于在下拉菜单中自动生成年/月/日
//**********************************************************
function YYYYMMDDstart()   
{   
	   MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];   

	   //先给年下拉框赋内容   
	   var y  = new Date().getFullYear();   
	   for (var i = (y-40); i < (y+1); i++) //以今年为准，前30年，后30年   
			   document.getElementsByName('year')[0].options.add(new Option(" "+ i +" 年", i));   

	   //赋月份的下拉框   
	   for (var i = 1; i < 13; i++)   
			   document.getElementsByName('month')[0].options.add(new Option(" " + i + " 月", i));   

	   document.getElementsByName('year')[0].value = y;   
	   document.getElementsByName('month')[0].value = new Date().getMonth() + 1;   
	   var n = MonHead[new Date().getMonth()];   
	   if (new Date().getMonth() ==1 && IsPinYear(YYYYvalue)) n++;   
			writeDay(n); //赋日期下拉框Author:meizz   
	   document.getElementsByName('day')[0].value = new Date().getDate();   
}   
if(document.attachEvent)   
   window.attachEvent("onload", YYYYMMDDstart);   
else   
   window.addEventListener('load', YYYYMMDDstart, false);   
function YYYYDD(str) //年发生变化时日期发生变化(主要是判断闰平年)   
{   
	   var MMvalue = document.getElementsByName('month')[0].options[document.getElementsByName('month')[0].selectedIndex].value;   
	   if (MMvalue == ""){ var e = document.getElementsByName('day')[0]; optionsClear(e); return;}   
	   var n = MonHead[MMvalue - 1];   
	   if (MMvalue ==2 && IsPinYear(str)) n++;   
			writeDay(n)   
}   
function MMDD(str)   //月发生变化时日期联动   
{   
	var YYYYvalue = document.getElementsByName('year')[0].options[document.getElementsByName('year')[0].selectedIndex].value;   
	if (YYYYvalue == ""){ var e = document.getElementsByName('day')[0]; optionsClear(e); return;}   
	var n = MonHead[str - 1];   
	if (str ==2 && IsPinYear(YYYYvalue)) n++;   
   writeDay(n)   
}   
function writeDay(n)   //据条件写日期的下拉框   
{   
	   var e = document.getElementsByName('day')[0]; optionsClear(e);   
	   for (var i=1; i<(n+1); i++)   
			e.options.add(new Option(" "+ i + " 日", i));   
}   
function IsPinYear(year)//判断是否闰平年   
{     return(0 == year%4 && (year%100 !=0 || year%400 == 0));}   
function optionsClear(e)   
{   
	e.options.length = 0;   
}   