<?php
header("Location:../../");
exit;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>虚拟机</title>
</head>
<body>
<?php
echo "centos";
?>
<APPLET CODE="../VncViewer.class" ARCHIVE="../VncViewer.jar"
        WIDTH="800" HEIGHT="632">
<PARAM NAME="PORT" VALUE="5907">
</APPLET>

</body>
<html>
