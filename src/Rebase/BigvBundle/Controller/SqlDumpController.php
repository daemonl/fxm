<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Venue;
use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Calendar;

use Rebase\BigvBundle\Common\BasicFunctions;

class SqlDumpController extends Controller
{
    
    public function indexAction()
    {
		$backups = array();	
		foreach(scandir("/var/www/bigv.rebase.com.au/OUT/SQL/", 1) as $i)
		{
			$parts = explode("_", $i);
			if (($parts[0] =="bv")&&(count($parts)==9))
			{
				$backups[] = new BackupRet($i);
			}
		}
 		return $this->render('RebaseBigvBundle:SqlDump:Index.html.twig', array('backups'=>$backups));
	}
	public function dumpAction(Request $request)
	{
		$name= BasicFunctions::GetSafeString($request->request->get('name'));
		$this->DoBackup($name);
		return $this->redirect($this->generateUrl('_RBV_sql_index'));
	}
	private function DoBackup($name)
	{
		$name = preg_replace('/[\s]/',"-", $name);
		$name = preg_replace('/[^a-zA-Z0-9-]/',"", $name);
		$n = new \DateTime();
		$fn = $n->format("Y_m_d_H_i_s_").$name."_";
		$em = $this->getDoctrine()->getEntityManager();
		$SettingParent = $em->getRepository('RebaseBigvBundle:Setting')->findOneByName("SqlParent");
		if (!$SettingParent)
		{
			$SettingParent = new Setting();
			$SettingParent->setName("SqlParent");
			$SettingParent->setValue("none");
		}
		$parent = $SettingParent->getValue();
		shell_exec("mysqldump --user=root --password=+987swee bigv2 >> /var/www/bigv.rebase.com.au/OUT/SQL/bv_$fn.sql");
		$SettingParent->setValue($fn);
		$em->persist($SettingParent);
	  	$em->flush(); 
	}
	public function readAction($filename)
	{
		$this->DoBackup("Pre Restore");
		shell_exec('mysql --user=root --password=+987swee -e "DROP DATABASE bigv2" ');
		shell_exec('mysql --user=root --password=+987swee -e "CREATE DATABASE bigv2" ');
		shell_exec('mysql --user=root --password=+987swee bigv2 < /var/www/bigv.rebase.com.au/OUT/SQL/'.$filename.'.sql');
		return $this->redirect($this->generateUrl('_RBV_sql_index'));
		//return $this->response("S");
	}
}

class BackupRet
{
	public $Label;
	public $Name;
	public $Filename; 
	public $Parent;
	public function __construct($filename)	
	{
		$f = file("/var/www/bigv.rebase.com.au/OUT/SQL/$filename");
		$parts = explode("-", $f[0]);

		if ($parts[2]=="RBV"){
		  if  ($parts[3] == "PARENT")
		  {
			  $this->Parent = $parts[4];
		  }
		}
		
		$this->Filename = substr($filename, 0, -4 );
		$parts = explode("_", $filename);
		$d = new \DateTime("$parts[1]-$parts[2]-$parts[3] $parts[4]:$parts[5]:$parts[6]");
		$this->Label = $d->format("D d M, h:i:s A");
		$this->Name = $parts[7];
	}
	
}