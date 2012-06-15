<?php

$fails=urldecode($_POST['file']);

if(!(file_exists($fails) && substr($fails,0,strlen($fails)-strlen(strstr($fails, '/')))=="compare") && !(file_exists(preg_replace("/^compare\/pc\//ui","compare/human/",$fails))))
{
	echo "Problēma ar faila ielādi!";
	die();
}

ini_set("error_reporting",2147483647);
$kategorijas["cat10"]["name"]="persona";
$kategorijas["cat10"]["tag"]="PERSONA";
$kategorijas["cat10"]["type"]="";

$kategorijas["cat11"]["name"]="persona.hum";
$kategorijas["cat11"]["tag"]="PERSONA";
$kategorijas["cat11"]["type"]="persona.hum";

$kategorijas["cat12"]["name"]="persona.anim";
$kategorijas["cat12"]["tag"]="PERSONA";
$kategorijas["cat12"]["type"]="persona.anim";

$kategorijas["cat13"]["name"]="persona.imag";
$kategorijas["cat13"]["tag"]="PERSONA";
$kategorijas["cat13"]["type"]="persona.imag";


$kategorijas["cat20"]["name"]="loc";
$kategorijas["cat20"]["type"]="";
$kategorijas["cat20"]["tag"]="LOKACIJA";

$kategorijas["cat21"]["name"]="loc.geo";
$kategorijas["cat21"]["type"]="loc.geo";
$kategorijas["cat21"]["tag"]="LOKACIJA";

$kategorijas["cat22"]["name"]="loc.addr";
$kategorijas["cat22"]["type"]="loc.addr";
$kategorijas["cat22"]["tag"]="LOKACIJA";
/*
$kategorijas["cat23"]["name"]="loc.addr.phone";
$kategorijas["cat23"]["type"]="loc.addr.phone";
$kategorijas["cat23"]["tag"]="LOKACIJA";

$kategorijas["cat24"]["name"]="loc.addr.postal";
$kategorijas["cat24"]["type"]="loc.addr.postal";
$kategorijas["cat24"]["tag"]="LOKACIJA";

$kategorijas["cat25"]["name"]="loc.addr.email";
$kategorijas["cat25"]["type"]="loc.addr.email";
$kategorijas["cat25"]["tag"]="LOKACIJA";

$kategorijas["cat26"]["name"]="loc.addr.url";
$kategorijas["cat26"]["type"]="loc.addr.url";
$kategorijas["cat26"]["tag"]="LOKACIJA";
*/
$kategorijas["cat27"]["name"]="loc.gsp";
$kategorijas["cat27"]["type"]="loc.gsp";
$kategorijas["cat27"]["tag"]="LOKACIJA";

$kategorijas["cat28"]["name"]="loc.other";
$kategorijas["cat28"]["type"]="loc.other";
$kategorijas["cat28"]["tag"]="LOKACIJA";



$kategorijas["cat30"]["name"]="org";
$kategorijas["cat30"]["type"]="";
$kategorijas["cat30"]["tag"]="ORGANIZACIJA";

$kategorijas["cat31"]["name"]="org.pol";
$kategorijas["cat31"]["type"]="org.pol";
$kategorijas["cat31"]["tag"]="ORGANIZACIJA";
/*
$kategorijas["cat32"]["name"]="org.edu";
$kategorijas["cat32"]["type"]="ord.edu";
$kategorijas["cat32"]["tag"]="ORGANIZACIJA";
*/
$kategorijas["cat33"]["name"]="org.gov";
$kategorijas["cat33"]["type"]="org.goc";
$kategorijas["cat33"]["tag"]="ORGANIZACIJA";

$kategorijas["cat34"]["name"]="org.com";
$kategorijas["cat34"]["type"]="org.com";
$kategorijas["cat34"]["tag"]="ORGANIZACIJA";

$kategorijas["cat35"]["name"]="org.game";
$kategorijas["cat35"]["type"]="org.game";
$kategorijas["cat35"]["tag"]="ORGANIZACIJA";
/*
$kategorijas["cat36"]["name"]="org.ethnic";
$kategorijas["cat36"]["type"]="org.ethnic";
$kategorijas["cat36"]["tag"]="ORGANIZACIJA";
*/
$kategorijas["cat37"]["name"]="org.other";
$kategorijas["cat37"]["type"]="org.other";
$kategorijas["cat37"]["tag"]="ORGANIZACIJA";




$kategorijas["cat41"]["name"]="Facility";
$kategorijas["cat41"]["tag"]="IESTADE";
$kategorijas["cat41"]["type"]="";


$kategorijas["cat51"]["name"]="Event";
$kategorijas["cat51"]["tag"]="NOTIKUMS";
$kategorijas["cat51"]["type"]="";


$kategorijas["cat60"]["name"]="prod";
$kategorijas["cat60"]["type"]="";
$kategorijas["cat60"]["tag"]="PRODUKTI";

$kategorijas["cat61"]["name"]="prod.brand";
$kategorijas["cat61"]["type"]="prod.brand";
$kategorijas["cat61"]["tag"]="PRODUKTI";

$kategorijas["cat62"]["name"]="prod.vehicle";
$kategorijas["cat62"]["type"]="prod.vehicle";
$kategorijas["cat62"]["tag"]="PRODUKTI";

$kategorijas["cat63"]["name"]="prod.award";
$kategorijas["cat63"]["type"]="prod.award";
$kategorijas["cat63"]["tag"]="PRODUKTI";

$kategorijas["cat64"]["name"]="prod.art";
$kategorijas["cat64"]["type"]="prod.art";
$kategorijas["cat64"]["tag"]="PRODUKTI";

$kategorijas["cat65"]["name"]="prod.printing";
$kategorijas["cat65"]["type"]="prod.printing";
$kategorijas["cat65"]["tag"]="PRODUKTI";


$kategorijas["cat70"]["name"]="time";
$kategorijas["cat70"]["tag"]="LAIKS";
$kategorijas["cat70"]["type"]="";

$kategorijas["cat71"]["name"]="time.date";
$kategorijas["cat71"]["tag"]="LAIKS";
$kategorijas["cat71"]["type"]="time.date";
/*
$kategorijas["cat72"]["name"]="time.date.abs";
$kategorijas["cat72"]["tag"]="LAIKS";
$kategorijas["cat72"]["type"]="time.date.abs";

$kategorijas["cat73"]["name"]="time.date.rel";
$kategorijas["cat73"]["tag"]="LAIKS";
$kategorijas["cat73"]["type"]="time.date.rel";

$kategorijas["cat74"]["name"]="time.hour";
$kategorijas["cat74"]["tag"]="LAIKS";
$kategorijas["cat74"]["type"]="time.hour";
*/
$kategorijas["cat75"]["name"]="time.other";
$kategorijas["cat75"]["tag"]="LAIKS";
$kategorijas["cat75"]["type"]="time.other";

$kategorijas["cat81"]["name"]="Citi";
$kategorijas["cat81"]["tag"]="CITI";
$kategorijas["cat81"]["type"]="";


function TextFromFile($path)
{
	$content=file_get_contents($path);
	preg_match("/\<TEXT\>([\w\W]*)\<\/TEXT\>/iu",$content,$t);
	$ret=$t[1];
	$ret=str_replace("</p>","<br />",$ret);
	$ret=str_replace("<p>","",$ret);
	$ret=preg_replace("/[\n\t\r ]+/ui"," ",$ret);
	return $ret;
}

class tagNode
{
	public $details=Array();
	public $startPosition;
	public $endPosition=-1;
	public $symbolsBefore;
	public $symbolsAfter;
	public $correctType=false;
	public $correctLength=false;
}

function charAt($string,$position)
{
	return mb_substr($string,$position,1);
}

function readAllTags($txt)
{
	$arr=Array();
	$i=0;
	$symbols=0;
	$c=charAt($txt,$i);
	
	$inTag=false;
	$betweenTags=0;
	$tag="";
	$tagStart=0;
	while($c!="")
	{	
		
		if($inTag)
		{
			$tag.=$c;
			if($c==">")
			{
				$inTag=false;
				if(preg_match("/\<(\/)?([a-z.]+)( type\=\"([a-z\.]+)\")?\>/ui",$tag,$r))
				{				
					if($r[1]=="")
					{
						if($betweenTags==0)
						{
							$node=new tagNode;
							$node->symbolsBefore=$symbols;
							$node->startPosition=$tagStart;
							$node->details=$r;
							$arr[]=$node;
						}
						$betweenTags++;
					}
					else
					{
						if($betweenTags==1)
						{
							$j=count($arr)-1;
							while($j>=0)
							{
								if($arr[$j]->endPosition==-1 && $arr[$j]->details[2]==$r[2])
								{
									$arr[$j]->symbolsAfter=$symbols;
									$node->endPosition=$tagStart+strlen($r[0]);
									break;
								}
								$j--;
							}
						}
						$betweenTags--;
					}
				}
			}
			
		}
		else
		{
			if($c=="<")
			{
				$tagStart=$i;
				$tag="<";
				$inTag=true;
			}
			else
			{
				if(!in_array($c,Array("\t","\n","r"," ")))
				{
					$symbols++;
				}
			}
		}
		$i++;
		$c=charAt($txt,$i);
	}
	return $arr;
}

$humanTXT=TextFromFile(preg_replace("/^compare\/pc\//ui","compare/human/",$fails));
$humanTAGS=readAllTags($humanTXT);

$pcTXT=TextFromFile($fails);
$pcTAGS=readAllTags($pcTXT);

/*
  0 => string '<PERSONA type="persona.hum">' (length=28)
  1 => string '' (length=0)
  2 => string 'PERSONA' (length=7)
  3 => string ' type="persona.hum"' (length=19)
  4 => string 'persona.hum' (length=11)
*/

/*
class tagNode
{
	public $details=Array();
	public $startPosition;
	public $endPosition=-1;
	public $symbolsBefore;
	public $symbolsAfter;
	public $correctType=false;
	public $correctLength=false;
}
*/

$total=count($humanTAGS);

	$i=0;
foreach($pcTAGS AS $Ptag)
{
	while($i<$total)
	{
		$Htag=&$humanTAGS[$i];
		if($Ptag->symbolsBefore==$Htag->symbolsBefore && $Ptag->symbolsAfter==$Htag->symbolsAfter)
		{
			$Ptag->correctLength=true;
			$Htag->correctLength=true;
			if($Ptag->details[2]==$Htag->details[2])
			{
				$Ptag->correctType=true;
				$Htag->correctType=true;
			}
			break;
		}
		elseif
			(
				$Ptag->details[2]==$Htag->details[2] && 
				(
					($Ptag->symbolsBefore>=$Htag->symbolsBefore && $Ptag->symbolsBefore<=$Htag->symbolsAfter) ||
					($Ptag->symbolsAfter<=$Htag->symbolsAfter && $Ptag->symbolsAfter>=$Htag->symbolsBefore) ||
					($Htag->symbolsBefore>=$Ptag->symbolsBefore && $Htag->symbolsBefore<=$Ptag->symbolsAfter) ||
					($Htag->symbolsAfter<=$Ptag->symbolsAfter && $Htag->symbolsAfter>=$Ptag->symbolsBefore)
				)
			)
		{
			$Ptag->correctLength=false;
			$Htag->correctLength=false;
			$Ptag->correctType=true;
			$Htag->correctType=true;
			break;
		}
		elseif($Ptag->symbolsAfter<$Htag->symbolsBefore)
		{
			break;
		}
		$i++;
	}
}


$s=&$pcTXT;
$tags=&$pcTAGS;
foreach($tags AS &$tag)
{
	if($tag->correctType==false || $tag->correctLength==false)
	{
		$new1='<span class="diff">';
		$s=mb_substr($s,0,$tag->startPosition).$new1.mb_substr($s,$tag->startPosition);
		
		$new2='</span>';
		$s=mb_substr($s,0,$tag->endPosition+strlen($new1)).$new2.mb_substr($s,$tag->endPosition+strlen($new1));
		
		foreach($tags AS &$tag2)
		{
			if($tag2->startPosition>$tag->startPosition)
			{
				$tag2->startPosition+=strlen($new1);
				$tag2->endPosition+=strlen($new1);
			}
			
			if($tag2->startPosition>$tag->endPosition)
			{
				$tag2->startPosition+=strlen($new2);
				$tag2->endPosition+=strlen($new2);
			}
			
		}
	}
}

$s=&$humanTXT;
$tags=&$humanTAGS;
foreach($tags AS &$tag)
{
	if($tag->correctType==false || $tag->correctLength==false)
	{
		$new1='<span class="diff">';
		$s=mb_substr($s,0,$tag->startPosition).$new1.mb_substr($s,$tag->startPosition);
		
		$new2='</span>';
		$s=mb_substr($s,0,$tag->endPosition+strlen($new1)).$new2.mb_substr($s,$tag->endPosition+strlen($new1));
		
		foreach($tags AS &$tag2)
		{
			if($tag2->startPosition>$tag->startPosition)
			{
				$tag2->startPosition+=strlen($new1);
				$tag2->endPosition+=strlen($new1);
			}
			
			if($tag2->startPosition>$tag->endPosition)
			{
				$tag2->startPosition+=strlen($new2);
				$tag2->endPosition+=strlen($new2);
			}
			
		}
	}
}

foreach($kategorijas AS $key=>$array)
{
	$oTag="\<{$array['tag']}";
	if($array['type']!="")
		$oTag.=" type\=\"{$array['type']}\"";
	$oTag.="\>";
	$cTag="\<\/{$array['tag']}\>";
	$humanTXT=preg_replace("/$oTag(.*?)$cTag/ui","<span class=\"$key\">$1</span>",$humanTXT);
	$pcTXT=preg_replace("/$oTag(.*?)$cTag/ui","<span class=\"$key\">$1</span>",$pcTXT);
}


$neatpazisti=0;
$nepareizas_robezas=0;
$nepareizs_tips=0;
$par_daudz=0;
$pareizi=0;
$kopa=count($humanTAGS);
foreach($humanTAGS AS &$tag)
{
	if($tag->correctType==false && $tag->correctLength==false)
	{
		$neatpazisti++;
	}
	elseif($tag->correctType==false && $tag->correctLength==true)
	{
		$nepareizs_tips++;
	}
	elseif($tag->correctType==true && $tag->correctLength==false)
	{
		$nepareizas_robezas++;
	}
	else
	{
		$pareizi++;
	}
}

$kopa_atpaziti=count($pcTAGS);
foreach($pcTAGS AS &$tag)
{
	if($tag->correctType==false && $tag->correctLength==false)
	{
		$par_daudz++;
	}
}
?>
		<div id="statistika">
			<table>
				<tr>
					<td>
						
					</td>
					<td>
						precision
					</td>
					<td>
						recall
					</td>
				</tr>
				<tr>
					<td>
						Pareizas robežas un tagi
					</td>
					<td>
						<?php echo $pareizi." no ".$kopa." (".round(100*$pareizi/$kopa,1)."%)" ?>
					</td>
										<td>
						<?php echo $pareizi." no ".$kopa_atpaziti." (".round(100*$pareizi/$kopa_atpaziti,1)."%)" ?>
					</td>
				</tr>
				<tr>
					<td>
						Pareizas vismaz robežas
					</td>
					<td>
						<?php echo ($nepareizs_tips+$pareizi)." no ".$kopa." (".round(100*($nepareizs_tips+$pareizi)/$kopa,1)."%)" ?>
					</td>
					<td>
						<?php echo ($nepareizs_tips+$pareizi)." no ".$kopa_atpaziti." (".round(100*($nepareizs_tips+$pareizi)/$kopa_atpaziti,1)."%)" ?>
					</td>
				</tr>
				<tr>
					<td>
						Pareizs vismaz tags
					</td>
					<td>
						<?php echo ($nepareizas_robezas+$pareizi)." no ".$kopa." (".round(100*($nepareizas_robezas+$pareizi)/$kopa,1)."%)" ?>
					</td>
					<td>
						<?php echo ($nepareizas_robezas+$pareizi)." no ".$kopa_atpaziti." (".round(100*($nepareizas_robezas+$pareizi)/$kopa_atpaziti,1)."%)" ?>
					</td>
				</tr>
				<tr>
					<td>
						Dators marķējis, cilvēks nav marķējis
					</td>
					<td>
						<?php echo $par_daudz." no ".$kopa." (".round(100*$par_daudz/$kopa,1)."%)" ?>
					</td>
					<td>
						<?php echo $par_daudz." no ".$kopa_atpaziti." (".round(100*$par_daudz/$kopa_atpaziti,1)."%)" ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="c">
			<div id="cont1">
				<h1>Cilvēka</h1>
				<?
					echo $humanTXT;
				?>
			</div>
			<div id="cont2">
				<h1>Datora</h1>
				<?
					echo $pcTXT;
				?>
			</div>
		</div>

