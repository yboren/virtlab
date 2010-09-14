<FORM action='download.php' method=post encType=multipart/form-data>
<div align="center" style='background:yellow'>
<b>
<font face="Verdana" ><span style='font-size:20pt'>输入下载代码以下载文件</span><br></font></b>      


<TABLE cellSpacing=0 cellPadding=0 border=0 height="60" ><TBODY>
<TR>
<TD><FONT face="Verdana" size="4"><b>这里输入</b></FONT>    
  <INPUT size=20 name="ref"     value="<?php error_reporting(0); echo $_GET[ref]; ?>"   >   
</TD>
<TD vAlign=bottom>
<INPUT type="submit" height=27 width=174 value="下载" border=0 name=submit valign="bottom">
</TD>
</TR>

</TBODY>
</TABLE>
	</div>
</FORM>