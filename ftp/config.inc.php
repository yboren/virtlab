<?php

$dbadd = "localhost";								//���mysql��ַ�����Ǿ��Ǳ������øġ�
$dbuser = "root";									//mysql�û��� ----��Ҫ�޸�----
$dbpass = "12347890";								//mysql���� ----��Ҫ�޸�----
$dbname = "download";								//mysql������֣���ǰ��һ�����������Ѿ��еġ� ----��Ҫ�޸�----
$basedir = "E:/javafogre/docs/PHP_Mysql_0605/sources/12/";		//��վ�����ڷ������ϵ�·�� ----��Ҫ�޸�----
$baseurl = "http://localhost/12/";					//��վ��url ----��Ҫ�޸�----
$admin = "111";										//��̨�������� ----��Ҫ�޸�----
$page_size = 10;									//��̨�ļ��б�ÿҳ������ ----�����޸�----
$allow_ext = false;
$savedir_type = "TYPE";			// TYPE | DATE
$debug = false;										//�����õģ�ƽʱ�����ó�false�Ϳ����ˡ� ----���ø�----
include("_allow_ext_list.inc.php");					//��չ�����ã��ɺ�̨���ɵ� ----���ܸ�----
include("_allow_ip_list.inc.php");						//��IP���ã��ɺ�̨���� ----���ܸ�----
include("_allow_size.inc.php");						//�ϴ��������ƣ��ͺ�̨���ɡ� ----���ܸ�----
include("_time_limit.inc.php");
include("_upload_dir.inc.php");
?>