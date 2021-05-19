<?php

	session_start();

	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case'pdf':
			
			require_once("../fpdf/fpdf.php");
			
			$pdf= new FPDF("P","pt","A4");
			$pdf->AddPage();
			 
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(0,5,"Estoque Produto CST",0,1,'C');
			$pdf->Cell(0,5,"","B",1,'C');
			$pdf->Ln(50);
			 
			//cabeçalho da tabela 
			$pdf->SetFont('arial','B',10);
			$pdf->SetFillColor(200,200,200);
			$pdf->Cell(250,20,'PRODUTO',1,0,"L",true);
			$pdf->Cell(100,20,'ESTOQUE ATUAL',1,0,"L",true);
			$pdf->Cell(100,20,utf8_decode('CUSTO MÉDIO'),1,0,"L",true);
			$pdf->Cell(100,20,'SUB TOTAL',1,1,"L",true);
			 
			//linhas da tabela
			$pdf->SetFont('arial','',7);
			
			$dao = new ProdutosDAO();
			$vet = $dao->RelatorioProdutoEstoqueAtu();
			$num = count($vet);
			$xgrup = "";
			
			for($i = 0; $i < $num; $i++){
				
				$pro	   = $vet[$i];
				
				$codigo    = $pro->getCodigo();
				$descricao = $pro->getDescricao();
				$estatu    = $pro->getEstoqueAtual();
				$descgrup  = $pro->getGrupo();
				$codgrupo  = $pro->getCodGrupo();
				$customedio= $pro->getCustoMedio();	
				$totqtd    = $estatu * $customedio;
				
				if($codgrupo != $xgrup){
					$xgrup = $codgrupo;
										
					$x = $pdf->GetX();
					$y = $pdf->GetY();
					$pdf->SetFillColor(200,200,200);
					$pdf->MultiCell(250, 20, '('.$codgrupo.') - '.utf8_encode($descgrup),1,'L', true);
					$pdf->SetXY($x + 250, $y);
					$pdf->MultiCell(100, 20, '',1,'L', true);					
					$pdf->SetXY($x + 350, $y);
					$pdf->MultiCell(100, 20, '',1,'L', true);
					$pdf->SetXY($x + 450, $y);
					$pdf->MultiCell(100, 20, '',1,'L', true);
					
				}
				
				if(isset($_REQUEST['rd'])){
					
					if(trim(number_format($estatu,2)) > 0){
						$x = $pdf->GetX();
						$y = $pdf->GetY();														
						/*$pdf->Cell(350,20,'('.$codigo.')'.utf8_encode($descricao),1,0,"L");
						$pdf->Cell(50,20,number_format($estatu,2,',','.'),1,0,"R");
						$pdf->Cell(50,20,number_format($customedio,2,',','.'),1,0,"R");
						$pdf->Cell(50,20,number_format($totqtd,2,',','.'),1,1,"R");*/
						$pdf->SetFont('arial','',7);
						$pdf->MultiCell(250, 20, substr('('.$codigo.') '.utf8_encode($descricao),0,50),1,'L', false);
						$pdf->SetXY($x + 250, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($estatu,2,',','.'),1,'R', false);					
						$pdf->SetXY($x + 350, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($customedio,2,',','.'),1,'R', false);
						$pdf->SetXY($x + 450, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($totqtd,2,',','.'),1,'R', false);						
					}
				}else{
					$x = $pdf->GetX();
					$y = $pdf->GetY();
					$pdf->SetFont('arial','',7);
						$pdf->MultiCell(250, 20, substr('('.$codigo.') '.utf8_encode($descricao),0,50),1,'L', false);
						$pdf->SetXY($x + 250, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($estatu,2,',','.'),1,'R', false);					
						$pdf->SetXY($x + 350, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($customedio,2,',','.'),1,'R', false);
						$pdf->SetXY($x + 450, $y);
						$pdf->SetFont('arial','',10);
						$pdf->MultiCell(100, 20, number_format($totqtd,2,',','.'),1,'R', false);	
				}
				if((int)$pdf->GetY() >= 750){
					$pdf->AddPage();
				}
				
			}
			
			$pdf->Output("estoqueprodutoCST.pdf","D");
		break;	

		}
	}

?>