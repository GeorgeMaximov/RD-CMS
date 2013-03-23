<?
	if (isset($_REQUEST['term'])){
		$rw=strtolower(htmlspecialchars($_REQUEST['term']));
		$sql=mysql_query("SELECT `Tags` from `News`;");
		$_tgs='';
		while ($row = mysql_fetch_assoc($sql)) {
			if ($row['Tags']!==NULL){
				$_tgs.=", ".strtolower($row['Tags']);
			}
		}
		$_tgs = preg_replace('/\s[\s]+/','',$_tgs);
		$_tgs = str_replace(", ",",",$_tgs);
		$_tgs = explode(",",$_tgs);
		$_tgs = array_unique($_tgs);
		unset($_tgs[0]);
		$content='[';
		$i=0;
		foreach($_tgs as $tag){
			if (strpos($tag,$rw)!==FALSE){
				if ($i!=0){$content.=',';}
				$content.='{"id":'.$i.',"value":"'.$tag.'"}';
				$i++;
			}
		}
		$content.=']';
	} else {
		$content="[]";
	}
?>