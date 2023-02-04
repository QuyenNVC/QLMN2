<?php

namespace App\Services;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet_Drawing;
use App\Models\Info;

class ExcelService
{
    /**
     * Export data to excel file
     * @author HaoDT
     */
    public static function export($data, $title, $column, $filename, $numRowStartRender)
    {
        $filenamePath = '/excel/' . $filename;
        $excel = new PHPExcel();
        $excel->createSheet();
        $activeSheet = $excel->setActiveSheetIndex(1);
        $activeSheet->setTitle($title);

        // header info school
        $infoSchool = Info::first();

        if ($infoSchool->logo) {
            $objDrawingPType = new PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($excel->setActiveSheetIndex(1));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(public_path('storage/logo/' . $infoSchool->logo));
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(1);
            $objDrawingPType->setOffsetY(5);
            $objDrawingPType->setWidth(100);                 //set width, height
            $objDrawingPType->setHeight(100);
        }

        $excel->getActiveSheet()->setCellValue('C2', $infoSchool->school_name);
        $excel->getActiveSheet()->setCellValue('C3', 'Địa chỉ: ' . $infoSchool->address);
        $excel->getActiveSheet()->setCellValue('C4', 'Website: ' . $infoSchool->website);
        $excel->getActiveSheet()->setCellValue('C5', 'SDT: ' . $infoSchool->phone_number);
        $excel->getActiveSheet()->setCellValue('C6', 'Fax: ' . $infoSchool->fax);
        $excel->getActiveSheet()->setCellValue('C7', 'Người đại diện: ' . $infoSchool->representatives);
        $excel->getActiveSheet()->setCellValue('C8', 'Chức danh: ' . $infoSchool->title);

        // set bold and width for cells title
        $startCellTitle = '';
        $endCellTitle = '';

        foreach ($column as $key => $value) {
            if ($key == 0) {
                $startCellTitle = $value['column'];
            }
            if ($key == count($column) - 1) {
                $endCellTitle = $value['column'];
            }
            $excel->getActiveSheet()->getColumnDimension($value['column'][0])->setWidth($value['width']);
        }
        $excel->getActiveSheet()->getStyle("$startCellTitle:$endCellTitle")->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle("$startCellTitle:$endCellTitle")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        foreach ($column as $value) {
            $activeSheet->setCellValue($value['column'], $value['title']);
        }

        foreach ($data as $item) {
            foreach ($column as $colVal) {
                $columnCell =
                    (strlen($colVal['column']) == 3)  // A11
                    ?
                    $colVal['column'][0]
                    : ($colVal['column'][0] . $colVal['column'][1]);
                $columnCell .= $numRowStartRender;
                $valueCell = str_replace('\n', PHP_EOL, $item[$colVal['field']]);
                $excel->getActiveSheet()->setCellValue($columnCell, "$valueCell");
                if (isset($colVal['align']) & $colVal['align'] == 'center') {
                    $excel->getActiveSheet()->getStyle($columnCell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                } else if (isset($colVal['align']) & $colVal['align'] == 'right') {
                    $excel->getActiveSheet()->getStyle($columnCell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                } else {
                    $excel->getActiveSheet()->getStyle($columnCell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                }
                $excel->getActiveSheet()->getStyle($columnCell)->getAlignment()->setWrapText(true);
            }
            $numRowStartRender++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save(public_path($filenamePath));
        return [
            'url' => asset($filenamePath),
            'filename' => $filename,
        ];
    }
}
