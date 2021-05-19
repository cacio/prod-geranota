<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
require_once("../fpdf/fpdf.php");

class PDFREL extends FPDF{

	private $dba;
	private $data    = array();
  	private $options = array(
  		'filename' => '',
  		'destinationfile' => '',
  		'paper_size'=>'A4',
  		'orientation'=>'P'
  	);
	
	public function PDFREL($data = array(), $options = array()){
			
		$this->data    = $data;
    	$this->options = $options;	
			
	}
	
	public function rptDetailData () {
		//
		//$border = 0;
		$this->AddPage();
		//$this->SetAutoPageBreak(true,60);
		$this->AliasNbPages();
		//header
 		
		
		//$this->DadosHeader();
								
		$h = 20;
		$left = 40;
		$top = 80;
		#tableheader
		$this->SetFillColor(200,200,200);
		$left = $this->GetX();		
		$this->Ln($left);
		$this->SetFont('Helvetica', 'B', 10);				
		//$this->SetX($left); $this->Cell(0, 1, utf8_decode('Produtos/Serviços'),0,5);			
		//$this->Ln(6);
		$this->Cell(20,$h,' ',1,0,'L',true);
		$this->SetX($left += 20); $this->Cell(270, $h, ''.utf8_decode('Descrição').'', 1, 0, 'C',true);
		$this->SetX($left += 270); $this->Cell(20, $h, 'Op', 1, 0, 'C',true);
		$this->SetX($left += 20); $this->Cell(30, $h, 'Qtd', 1, 0, 'C',true);
		$this->SetX($left += 30); $this->Cell(100, $h, 'Vlr. Un (R$)', 1, 0, 'C',true);
		$this->SetX($left += 100); $this->Cell(100, $h, 'Sub-Total (R$)', 1, 1, 'C',true);
		//$this->Ln(20);
 
		$this->SetFont('Helvetica','',9);
		$this->SetWidths(array(20,270,20,30,100,100));
		$this->SetAligns(array('C','L','C','C','R','R'));
		$no = 1; $this->SetFillColor(255);
		foreach ($this->data['data'] as $baris) {
			$this->Row(
				array($no++,
				$baris['nip'],
				$baris['nama'],
				$baris['alamat'],
				$baris['email'],
				$baris['website']
			));
		}
 		
		//$this->DadosFooter();

	}
 
	public function printPDF () {
 
		if ($this->options['paper_size'] == "F4") {
			$a = 8.3 * 72; //1 inch = 72 pt
			$b = 13.0 * 72;
			$this->FPDF($this->options['orientation'], "pt", array($a,$b));
		} else {
			$this->FPDF($this->options['orientation'], "pt", $this->options['paper_size']);
		}
 
	    $this->SetAutoPageBreak(false);
	    $this->AliasNbPages();
	    $this->SetFont("helvetica", "B", 10);
	    //$this->AddPage(); 						
		
	    $this->rptDetailData();
 
	    $this->Output($this->options['filename'],$this->options['destinationfile']);
  	}
 
  	private $widths;
	private $aligns;
 
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
 
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
 
	function Row($data)
	{
		$this->SetFont('Courier','',10);	
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=10*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			
			$this->MultiCell($w,10,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
 
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
 
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	function DadosHeader(){
		
		## abeçalho parte da imagem e dados da prodasiq
		$this->Image('../images/logologins.png',80,40,150);
		
		$this->SetXY(260, 40);				
		$this->SetFont('Helvetica', 'B', 12);				 				
		$this->Cell(0, 1, utf8_decode('PRODASIQ - Sistemas e Representações Ltda.'),0,10);
		
		$this->SetXY(260, 55);
		$this->SetFont('Helvetica', 'B', 10);
		$this->Cell(0, 1, utf8_decode('Rua Cel. Corte Real, 284 - Petrópolis - Porto Alegre / RS'),0,10); 				
		
		$this->SetXY(260, 70);
		$this->SetFont('Helvetica', 'B', 10);
		$this->Cell(0, 1, utf8_decode('(51) 3391 - 3625 | comercial@prodasiq.com.br'),0,10);
		
		$this->SetXY(260, 85);
		$this->SetFont('Helvetica', 'B', 10);
		$this->Cell(0, 1, utf8_decode('www.prodasiq.com.br'),0,10);
						
		$this->SetXY(25, 100);
		$this->SetFont('Helvetica', 'B', 16);
		$this->cell(0,20,utf8_decode('N°. ORÇAMENTO '.$this->data['orcamento']['numero_orcamento'].' '),1,0,'C');
		
		$this->SetXY(22, 140);				
		$this->SetFont('Helvetica', 'B', 10);
		$this->Cell(0, 1, utf8_decode('Dados Cliente'),0,10);
		
		$this->SetXY(487, 140);				
		$this->SetFont('Helvetica', 'B', 10);
		$this->Cell(0, 1, utf8_decode('Data:'.$this->data['orcamento']['data'].''),0,10);
		
		//$this->SetXY(25, 150);
		
		$this->SetXY(25, 160);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('Nome/Razão Social:'),0,10);
		
		$this->SetXY(130, 160);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_razao'].''),0,10);
		
		$this->SetXY(25, 175);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('CPF/CNPJ:'),0,10);
		
		$this->SetXY(130, 175);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_cnpj'].''),0,10);
		
		$this->SetXY(348, 175);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('I.E.'),0,10);
						
		$this->SetXY(375, 175);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_ins'].''),0,10);
		
		$this->SetXY(25, 190);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('Endereço:'),0,10);
		
		$this->SetXY(130, 185);
		$this->SetFont('Courier', '', 10);				
		//$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_ende'].''),0,10);
		$this->MultiCell(420, 10, utf8_decode(''.$this->data['cliente']['cli_ende'].''),0,'J', false);
		
		$this->SetXY(25, 210);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('Nome Contato:'),0,10);		
		
		$this->SetXY(130, 210);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_resp_sistema'].''),0,10);
		
		$this->SetXY(348, 210);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('Telefone(s)'),0,10);
		
		$this->SetXY(405, 210);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_telefone'].''),0,10);
		
		$this->SetXY(25, 225);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('E-Mail:'),0,10);
		
		$this->SetXY(130, 225);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_resp_email'].''),0,10);
		
		$this->SetXY(348, 225);
		$this->SetFont('Helvetica', 'B', 10);				
		$this->Cell(0, 1, utf8_decode('Representante(s)'),0,10);
		
		$this->SetXY(435, 225);
		$this->SetFont('Courier', '', 10);				
		$this->Cell(0, 1, utf8_decode(''.$this->data['cliente']['cli_representante'].''),0,10);
											
		$this->SetXY(25, 155);
		$this->MultiCell(0,80,'', 1,1);				
							
	}
	
	function DadosFooter(){
			//echo (int)$this->GetY();
			if((int)$this->GetY() >= 700){
				$this->AddPage();
			}
			$a = $this->GetX();
			$this->Ln($a - 5);
			$this->SetFont('Helvetica', 'B', 10);				
			$this->Cell(0, 4, utf8_decode('Descontos'),0,2);
			$this->Ln(5);
			//$this->Cell(160, 0, '',0,10);
																					
			$this->Cell(440,18,'',1,0,'C');
			$this->Cell(100,18,''.$this->data['desconto']['desconto'].'',1,0,'R');
			
			$this->Ln($a);
			$this->SetFont('Helvetica', 'B', 10);				
			$this->Cell(0, 4, utf8_decode('Totais'),0,2);
			$this->Ln(5);
			
			foreach($this->data['dados_totais'] as $totais){
			
				$this->Cell(440,18,''.$totais['opnome'].'',1,0,'L');
				$this->Cell(100,18,''.$totais['valor'].'',1,0,'R');						
				$this->Ln(18);
				
			}
			
			$this->Cell(485,15,'Total......',0,0,'R');
			$this->Cell(55,15,'R$ '.$this->data['valortotal']['valortotal'].'',0,0,'R');
			
			$this->Ln($a - 5);
			$this->SetFont('Helvetica', 'B', 10);				
			$this->Cell(0, 4, utf8_decode('Forma de Pagamento'),0,2);
			$this->Ln(3);
			$this->Cell(540,15,''.$this->data['formpag']['formpag'].'',1,0,'L');
			
			$this->Ln($a);
			$this->SetFont('Helvetica', 'B', 10);				
			$this->Cell(0, 4, utf8_decode('Observações'),0,2);
			$this->Ln(4);				
			$this->MultiCell(540, 20, utf8_decode(''.$this->data['observacao']['obs'].''),1,'J', false);
						
	}
	
	
}
?>