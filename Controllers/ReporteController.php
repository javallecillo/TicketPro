<?php
namespace Controllers;

// Incluir los modelos necesarios
use Models\User as UserModel;
use Models\Service as ServiceModel;
use Models\Role as RoleModel;


// 1. INCLUSIÓN DE LA CLASE PERSONALIZADA (CustomPDF)
require_once(ROOT . 'Lib' . DS . 'CustomPDF.php');

use \CustomPDF;

class ReporteController {
    
    private $userModel;
    private $serviceModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->serviceModel = new ServiceModel();
        $this->roleModel = new RoleModel();
    }

    /**
     * Genera el reporte en PDF de todos los usuarios.
     */
    public function ReporteUsuarios() {
        // 1. Obtener datos del Modelo
        // Nota: Asegúrate de que toList() devuelva un array de objetos o arrays
        $datosUsuarios = $this->userModel->toList(); 
        
        // 2. Inicializar el PDF usando tu clase personalizada
        $pdf = new \CustomPDF(); 
        $pdf->AliasNbPages(); // Habilita el conteo total de páginas {nb}
        
        // 3. Configurar el título (será usado en el Header)
        $pdf->setReporteTitulo('REPORTE DETALLADO DE USUARIOS DEL SISTEMA');
        
        // 4. Agregar la primera página (esto llama a Header() y Footer() automáticamente)
        $pdf->AddPage(); 
        
        // 5. Dibujar la tabla de datos
        $this->dibujarTablaUsuarios($pdf, $datosUsuarios);
        
        // 6. Salida del PDF (Mostrar en el navegador)
        // Puedes cambiar 'I' por 'D' si deseas que se descargue
        $pdf->Output('I', 'Reporte_Usuarios.pdf');
    }
    
    /**
     * Lógica para dibujar la tabla de usuarios en el PDF.
     * @param CustomPDF $pdf La instancia del PDF.
     * @param array $datos Los datos obtenidos del Modelo.
     */
    private function dibujarTablaUsuarios(CustomPDF $pdf, $datos) {

        $titulos = ['ID', 'USUARIO', 'NOMBRE COMPLETO', 'CORREO', 'TELEFONO', 'SERVICIO', 'ROL', 'ESTADO'];
        $anchos = [7, 22, 35, 30, 20, 30, 20, 16]; // Suma: 190mm (tamaño de la página A4)
        
        // Llama al método estandarizado para dibujar el encabezado de la tabla
        $pdf->TablaHeader($titulos, $anchos);
        
        $fill = false;
        foreach ($datos as $row) {
            $pdf->SetX(15);

            // Alternar color de fondo para las filas (true habilita el relleno)
            $pdf->SetFillColor($fill ? 230 : 255, 255, 255); 
            
            // Usamos iconv para convertir de UTF-8 (BD) a ISO-8859-1 (FPDF)
            $pdf->Cell($anchos[0], 6, $row->id, 'LR', 0, 'C', true);
            $pdf->Cell($anchos[1], 6, iconv('UTF-8', 'windows-1252', $row->username), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[2], 6, iconv('UTF-8', 'windows-1252', $row->name), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[3], 6, iconv('UTF-8', 'windows-1252', $row->email), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[4], 6, iconv('UTF-8', 'windows-1252', $row->phone), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[5], 6, iconv('UTF-8', 'windows-1252', $row->service_id), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[6], 6, iconv('UTF-8', 'windows-1252', $row->role_id), 'LR', 0, 'L', true);
            $pdf->Cell($anchos[7], 6, iconv('UTF-8', 'windows-1252', $row->status), 'LR', 0, 'L', true);
            
            $pdf->Ln();
            $fill = !$fill; // Cambia el color para la siguiente fila
            
        }
        // Dibuja la línea de cierre de la tabla
        $pdf->SetX(15);
        $pdf->Cell(array_sum($anchos), 0, '', 'T'); 
    }

    // Aquí iría ReporteProductos(), ReporteOrdenes(), etc.
}
?>