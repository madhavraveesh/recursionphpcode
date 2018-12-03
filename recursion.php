<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller
{
	public $urlobj = array();
	public $dataarr = array();
    
	public function __construct() {
        parent::__construct();
        
    }
	
	public function getUrl()
	{
		 $url = $_REQUEST['url'];
		 $result = $this->nextPhoto($url, "" ,3,0);
		
		 echo json_encode(array('status' => TRUE, 'data' => $result));
	}
	
	function fread_url($url,$ref="")
    {
        if(function_exists("curl_init")){
            $ch = curl_init();
            $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; ".
                          "Windows NT 5.0)";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt( $ch, CURLOPT_HTTPGET, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION , 1 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION , 1 );
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_REFERER, $ref );
            curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
            $html = curl_exec($ch);
            curl_close($ch);
        }
       else{
            $hfile = fopen($url,"r");
            if($hfile){
                while(!feof($hfile)){
                    $html.=fgets($hfile,1024);
                }
            } 
        }
        return $html;
    }
	
	public function nextPhoto($url, $ref="", $dataArrkey, $arraykey)
	{ 
	    static $count = 0;
		$depth = 3;
		$total_element = 11;
		if($count < $depth)
		{
		   $count++;
		}
	    $ch = curl_init();
        $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; ".
                          "Windows NT 5.0)";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt( $ch, CURLOPT_HTTPGET, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION , 1 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION , 1 );
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_REFERER, $ref );
            curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
            $html = curl_exec($ch);
		    curl_close($ch);
		    
		  preg_match_all ("/a[\s]+[^>]*?href[\s]?=[\s\"\']+".
							"(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", 
							$html, $matches);

		  $matches = $matches[1];
		  $list = array(); 
		    
		  if($dataArrkey == 0)
		  {
				return $this->urlobj;
	
		  }else{  
			       if($dataArrkey < $count)
				   {
					   $this->urlobj[$dataArrkey][$url] = $matches;
				   }else{
					   $this->urlobj[$count][$url] = $matches;
				   }
				   
				   foreach($matches as $matche)
				   {
					   if(strpos($matche, 'http') !== false)
					   {
						  $newArr[] =  $matche;		
					   }
				   }
				   if($dataArrkey < $count)
				   {
					   $looparr = $this->dataarr[$dataArrkey] = $newArr;
				   }else{
					   $looparr = $this->dataarr[$count] = $newArr;
				   }
				   
				   for( $i=$arraykey; $i < count($looparr); $i++)
				   {    
						if( $count < $depth  || $i < $total_element)
						{
							 return $this->nextPhoto($looparr[$i], "", $dataArrkey, ++$i); 
						}else{
							  $i=2;
						      return $this->nextPhoto($looparr[$i], "", --$dataArrkey, ++$i);  
					     }
				   }
		       } 
	   }
    
 
} //https://github.com/alchemy-fr/PHPMailer_v5.1/
