<?php
namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Export\ExGame;
use Rebase\BigvBundle\Entity\Club;
use Rebase\BigvBundle\Entity\Team;
use Rebase\BigvBundle\Entity\VenueTeamLink;

use Rebase\BigvBundle\Common\BasicFunctions;

include ("/var/www/bigv.rebase.com.au/src/Rebase/BigvBundle/Ext/PHPExcel.php");

class ExportController extends Controller 
{
    private $statRow;
    public function indexAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		$divisions = $em->getRepository('RebaseBigvBundle:Division')->findAll();
		$clubs = $em->getRepository('RebaseBigvBundle:Club')->findAll();
		$venues = $em->getRepository('RebaseBigvBundle:Venue')->findAll();
		return $this->render('RebaseBigvBundle:Export:index.html.twig', array('divisions'=> $divisions, 'clubs'=> $clubs, 'venues' => $venues));
	}
	
	public function excelAction(Request $request)
	{
		$FileName = "/var/www/bigv.rebase.com.au/OUT/out.xlsx";
		
		$author = BasicFunctions::GetSafeString($request->request->get('Author')); 
		$title = BasicFunctions::GetSafeString($request->request->get('Title'));
		
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator($author);
		$objPHPExcel->getProperties()->setLastModifiedBy($author);
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription($title);
		
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->setTitle('Stats');
		$objPHPExcel->getActiveSheet()->SetCellValue("A1", 'Sheet');
		$objPHPExcel->getActiveSheet()->SetCellValue("B1", 'Games');
		$objPHPExcel->getActiveSheet()->SetCellValue("C1", 'Warnings');
		$this->statRow = 2;
		
		$em = $this->getDoctrine()->getEntityManager();
		$this->WriteSheet("All Games", $title, "ALL", $this->GetGames("g.id = g.id") , $objPHPExcel);
		$divisions = $em->getRepository('RebaseBigvBundle:Division')->findAll();
		foreach($divisions as $D)
		{
			$this->WriteSheet($D->getFullName(), $title, $D->getName(), $this->GetGames("d.id = ".$D->getId()) , $objPHPExcel);
		}
		$clubs = $em->getRepository('RebaseBigvBundle:Club')->findAll();
		foreach($clubs as $C)
		{
			$this->WriteSheet($C->getName(), $title, $C->getShortName(), $this->GetGames("(hc.id = ".$C->getId()." OR ac.id = ".$C->getId().")") , $objPHPExcel);
		}
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($FileName);
		$response = new Response();
		$response->headers->set('Content-Description', 'File Transfer');
		$response->headers->set('Content-Type', 'application/vnd.ms-excel');
		$response->headers->set('Content-Disposition', 'attachment; filename=export.xlsx');
		$response->send();
		readfile($FileName);
		return $response;
	}
	private function WriteSheet($Title, $SubTitle, $Tab, $games, $objPHPExcel)
	{
		$round = 0;
		$lastGameID = 0;
		$objWorksheet1 = $objPHPExcel->createSheet();
		$objWorksheet1->setTitle($Tab);
		$row=4;
		$objWorksheet1->SetCellValue("A1", "BIG V");
		$objWorksheet1->SetCellValue("D1", $SubTitle);
		$objWorksheet1->SetCellValue("A2", $Title);

		$objWorksheet1->mergeCells('A1:C1');
		$objWorksheet1->mergeCells('D1:H1');
		$objWorksheet1->mergeCells('A2:H2');

		$objWorksheet1->getStyle('A1')->getFont()->setBold(true);
		$objWorksheet1->getStyle('A1')->getFont()->setSize(20);

		$objWorksheet1->getStyle('D1')->getFont()->setBold(true);
		$objWorksheet1->getStyle('A1')->getFont()->setSize(20);

		$objWorksheet1->getStyle('A2')->getFont()->setBold(true);
		$objWorksheet1->getStyle('A1')->getFont()->setSize(16);


		$objWorksheet1->SetCellValue("A3", "Round");
		$objWorksheet1->SetCellValue("B3", "Day");
		$objWorksheet1->SetCellValue("C3", "Date");
		$objWorksheet1->SetCellValue("D3", "Time");
		$objWorksheet1->SetCellValue("E3", "Venue");
		$objWorksheet1->SetCellValue("F3", "Division");
		$objWorksheet1->SetCellValue("G3", "Home");
		$objWorksheet1->SetCellValue("H3", "Away");

  foreach($games as $game)
  { 
    $objPHPExcel->getActiveSheet()->SetCellValue("A$row", $game->Round);
    $objWorksheet1->SetCellValue("B$row", $game->DateDay);
    $objWorksheet1->SetCellValue("C$row", $game->DateExcel);
    $objWorksheet1->getStyle("C$row")->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
    $objWorksheet1->SetCellValue("D$row", $game->TimeLong);
    $objWorksheet1->SetCellValue("E$row", $game->VenueShort);
    $objWorksheet1->SetCellValue("F$row", $game->DivisionShort);
    $objWorksheet1->SetCellValue("G$row", $game->HomeTeamLong);
    $objWorksheet1->SetCellValue("H$row", $game->AwayTeamLong);
    $row++;
  }
$row = $row - 1;

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue("A".$this->statRow, $Tab);
$objPHPExcel->getActiveSheet()->SetCellValue("B".$this->statRow, ($row-3));



if ($row==3){
  $objPHPExcel->getActiveSheet()->SetCellValue("C".$this->statRow, 'WARNING: NO GAMES FOUND');
}

$this->statRow ++;

  $objWorksheet1->getColumnDimension('A')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('B')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('C')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('D')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('E')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('F')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('G')->setAutoSize(true);
  $objWorksheet1->getColumnDimension('H')->setAutoSize(true);
  $objWorksheet1->getStyle("A3:H$row")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

  $objWorksheet1->getStyle('A2:H2')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THICK);
  $objWorksheet1->getStyle('A3:H3')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THICK);
	}
	
	public function AllAction()
	{
		return $this->DoSimple("g.id = g.id");
	}
	
	public function ByClubAction($clubID)
	{
		$clubIDINT = intval($clubID);
		return $this->DoSimple("(hc.id = $clubIDINT OR ac.id = $clubIDINT)");
	}	

	public function ByTeamAction($teamID)
	{
		$teamIDINT = intval($teamID);
		return $this->DoSimple("(h.id = $teamIDINT OR ac.id = $teamIDINT)");
	}	
	
	public function ByDivisionAction($divisionID)
	{
		$divisionIDINT = intval($divisionID);
		return $this->DoSimple("d.id = $divisionIDINT");
	}	
	
	public function ByVenueAction($venueID)
	{
		$venueIDINT = intval($venueID);
		return $this->DoSimple("v.id = $venueIDINT");
	}	
	
	private function GetGames($whereClause)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em
        ->createQuery('
            SELECT g, h, a, hc, ac, d FROM RebaseBigvBundle:Game g
            JOIN g.homeTeam h
			JOIN g.awayTeam a
			JOIN h.club hc
			JOIN a.club ac
			JOIN h.division d
			JOIN g.venue v
			WHERE '.$whereClause.'
			'
        );
		
		$games = $query->getResult();// $em->getRepository('RebaseBigvBundle:Game')->findAll();
		$export = array();
		foreach($games as $g)
		{
			$ex = new ExGame($g);
			$export[] = $ex;
		}
		return $export;
	}
	
	private function DoSimple($whereClause){
		$export = $this->GetGames($whereClause);
		return $this->render('RebaseBigvBundle:Export:simple.html.twig', array('exportTitle' => 'All Games', 'games'=> $export));
	}
}
?>