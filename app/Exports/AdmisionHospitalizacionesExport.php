<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * 
 */
class AdmisionHospitalizacionesExport implements FromArray, WithTitle, ShouldAutoSize, WithHeadings
{
	protected $titulo;
    protected $headings;
    protected $data;

    public function __construct($titulo,$headings,$data)
    {
    	$this->titulo = $titulo;
    	$this->headings = $headings;
    	$this->data = $data;
    }

    public function array(): array
    {
    	return $this->data;
    }

    public function title(): string
    {
    	return $this->titulo;
    }

    public function headings(): array
    {
    	return $this->headings;
    }
}