<html>
<head>

<title>Welcome to My World!</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<script language="javascript">
function IsHasWords()
{
    if (document.search.query_str.value.length == 0)
    {
        document.search.query_str.focus();
        return false;
    }
}
</script>

<body bgcolor="white" text="black">
<form name = "search" method="post" action="search.php" onSubmit="return  IsHasWords();" target="search_resut">
<input name="query_str" type="text" size=50/>
<input type="submit" value="Search"/>
</form>

<iframe name="search_resut" style="width:100%;height:90%" frameborder="no" border="0" >
</iframe>
</body>
</html>
