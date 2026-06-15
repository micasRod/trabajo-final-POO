<?php

declare(strict_types=1);

// ============================================================
// ENUM
// ============================================================

enum TipoVehiculo: string
{
    case CAMION_CARGA_PESADA = 'camion_carga_pesada';
    case UTILITARIO          = 'utilitario';
    case REFRIGERADO         = 'refrigerado';
}

// ============================================================
// INTERFACE
// ============================================================

interface IVehiculo
{
    public function realizarEntrega(): void;
    public function obtenerEstado(): string;
    public function obtenerCapacidad(): float;
}

// ============================================================
// COMPONENTE (abstracto)
// ============================================================

abstract class Componente
{
    protected string $codigo;
    protected string $estado;

    public function __construct(string $codigo, string $estado = 'operativo')
    {
        $this->codigo = $codigo;
        $this->estado = $estado;
    }

    abstract public function diagnosticar(): void;

    public function getCodigo(): string { return $this->codigo; }
    public function getEstado(): string { return $this->estado; }
}

class Motor extends Componente
{
    private float $potencia;

    public function __construct(string $codigo, float $potencia = 0.0, string $estado = 'operativo')
    {
        parent::__construct($codigo, $estado);
        $this->potencia = $potencia;
    }

    public function diagnosticar(): void
    {
        echo "  [Motor {$this->codigo}] potencia: {$this->potencia} cv — estado: {$this->estado}\n";
    }

    public function getPotencia(): float { return $this->potencia; }
}

class Chasis extends Componente
{
    private string $tipo;

    public function __construct(string $codigo, string $tipo = 'estándar', string $estado = 'operativo')
    {
        parent::__construct($codigo, $estado);
        $this->tipo = $tipo;
    }

    public function diagnosticar(): void
    {
        echo "  [Chasis {$this->codigo}] tipo: {$this->tipo} — estado: {$this->estado}\n";
    }

    public function getTipo(): string { return $this->tipo; }
}

class Carroceria extends Componente
{
    private string $material;

    public function __construct(string $codigo, string $material = 'acero', string $estado = 'operativo')
    {
        parent::__construct($codigo, $estado);
        $this->material = $material;
    }

    public function diagnosticar(): void
    {
        echo "  [Carrocería {$this->codigo}] material: {$this->material} — estado: {$this->estado}\n";
    }

    public function getMaterial(): string { return $this->material; }
}

// ============================================================
// EMPRESA (abstracta)
// ============================================================

abstract class Empresa
{
    protected string $nombre;
    protected string $direccion;
    protected string $telefono;

    public function __construct(string $nombre, string $direccion, string $telefono)
    {
        $this->nombre    = $nombre;
        $this->direccion = $direccion;
        $this->telefono  = $telefono;
    }

    abstract public function operar(): void;

    public function getNombre(): string    { return $this->nombre; }
    public function getDireccion(): string { return $this->direccion; }
    public function getTelefono(): string  { return $this->telefono; }
}

// ============================================================
// VEHICULO (abstracto)
// ============================================================

abstract class Vehiculo implements IVehiculo
{
    protected string    $id;
    protected string    $patente;
    protected string    $marca;
    protected string    $estado;
    protected Motor     $motor;
    protected Chasis    $chasis;
    protected Carroceria $carroceria;

    public function __construct(
        string $id,
        string $patente,
        string $marca,
        string $estado = 'disponible'
    ) {
        $this->id         = $id;
        $this->patente    = $patente;
        $this->marca      = $marca;
        $this->estado     = $estado;
        $this->motor      = new Motor("MOT-{$id}");
        $this->chasis     = new Chasis("CHA-{$id}");
        $this->carroceria = new Carroceria("CAR-{$id}");
    }

    abstract public function realizarEntrega(): void;

    public function obtenerEstado(): string   { return $this->estado; }
    public function obtenerCapacidad(): float { return 0.0; }

    public function getId(): string           { return $this->id; }
    public function getPatente(): string      { return $this->patente; }
    public function getMarca(): string        { return $this->marca; }
    public function getMotor(): Motor         { return $this->motor; }
    public function getChasis(): Chasis       { return $this->chasis; }
    public function getCarroceria(): Carroceria { return $this->carroceria; }
}

// ============================================================
// VEHICULOS PROPIOS
// ============================================================

class CamionCargaPesada extends Vehiculo
{
    private float  $capacidadTon;
    private string $tipoRemolque;

    public function __construct(
        string $id,
        string $patente,
        string $marca,
        float  $capacidadTon,
        string $tipoRemolque,
        string $estado = 'disponible'
    ) {
        parent::__construct($id, $patente, $marca, $estado);
        $this->capacidadTon = $capacidadTon;
        $this->tipoRemolque = $tipoRemolque;
    }

    public function realizarEntrega(): void
    {
        $this->cargarMercancia();
        echo "CamionCargaPesada [{$this->patente}]: entrega de carga pesada — {$this->capacidadTon} ton\n";
    }

    public function cargarMercancia(): void
    {
        echo "CamionCargaPesada [{$this->patente}]: cargando mercancía con remolque '{$this->tipoRemolque}'\n";
    }

    public function obtenerCapacidad(): float  { return $this->capacidadTon; }
    public function getCapacidadTon(): float   { return $this->capacidadTon; }
    public function getTipoRemolque(): string  { return $this->tipoRemolque; }
}

class Utilitario extends Vehiculo
{
    private string $zonaCobertura;
    private float  $capacidadM3;

    public function __construct(
        string $id,
        string $patente,
        string $marca,
        string $zonaCobertura,
        float  $capacidadM3,
        string $estado = 'disponible'
    ) {
        parent::__construct($id, $patente, $marca, $estado);
        $this->zonaCobertura = $zonaCobertura;
        $this->capacidadM3   = $capacidadM3;
    }

    public function realizarEntrega(): void
    {
        $this->navegarZonaUrbana();
        echo "Utilitario [{$this->patente}]: entrega en zona '{$this->zonaCobertura}' — {$this->capacidadM3} m³\n";
    }

    public function navegarZonaUrbana(): void
    {
        echo "Utilitario [{$this->patente}]: navegando zona urbana '{$this->zonaCobertura}'\n";
    }

    public function obtenerCapacidad(): float  { return $this->capacidadM3; }
    public function getZonaCobertura(): string { return $this->zonaCobertura; }
    public function getCapacidadM3(): float    { return $this->capacidadM3; }
}

class VehiculoRefrigerado extends Vehiculo
{
    private float  $tempMin;
    private float  $tempMax;
    private string $tipoCarga;

    public function __construct(
        string $id,
        string $patente,
        string $marca,
        float  $tempMin,
        float  $tempMax,
        string $tipoCarga,
        string $estado = 'disponible'
    ) {
        parent::__construct($id, $patente, $marca, $estado);
        $this->tempMin   = $tempMin;
        $this->tempMax   = $tempMax;
        $this->tipoCarga = $tipoCarga;
    }

    public function realizarEntrega(): void
    {
        $this->verificarCadenaFrio();
        $this->controlarTemperatura();
        echo "VehiculoRefrigerado [{$this->patente}]: entrega de '{$this->tipoCarga}' ({$this->tempMin}°C / {$this->tempMax}°C)\n";
    }

    public function controlarTemperatura(): void
    {
        echo "VehiculoRefrigerado [{$this->patente}]: temperatura controlada entre {$this->tempMin}°C y {$this->tempMax}°C\n";
    }

    public function verificarCadenaFrio(): void
    {
        echo "VehiculoRefrigerado [{$this->patente}]: verificando cadena de frío para '{$this->tipoCarga}'\n";
    }

    public function obtenerCapacidad(): float { return 0.0; }
    public function getTempMin(): float       { return $this->tempMin; }
    public function getTempMax(): float       { return $this->tempMax; }
    public function getTipoCarga(): string    { return $this->tipoCarga; }
}

// ============================================================
// VEHICULO EXTERNO
// ============================================================

class VehiculoExterno implements IVehiculo
{
    private string       $empresaOrigen;
    private float        $capacidad;
    private string       $estado;
    private TipoVehiculo $tipoVehiculo;

    public function __construct(
        string       $empresaOrigen,
        float        $capacidad,
        TipoVehiculo $tipoVehiculo,
        string       $estado = 'disponible'
    ) {
        $this->empresaOrigen = $empresaOrigen;
        $this->capacidad     = $capacidad;
        $this->tipoVehiculo  = $tipoVehiculo;
        $this->estado        = $estado;
    }

    public function realizarEntrega(): void
    {
        echo "VehiculoExterno [{$this->empresaOrigen} — {$this->tipoVehiculo->value}]: realizando entrega\n";
    }

    public function obtenerEstado(): string   { return $this->estado; }
    public function obtenerCapacidad(): float { return $this->capacidad; }
    public function getTipo(): TipoVehiculo   { return $this->tipoVehiculo; }
    public function getEmpresaOrigen(): string { return $this->empresaOrigen; }
}

// ============================================================
// EMPRESA ALIADA
// ============================================================

class EmpresaAliada extends Empresa
{
    /** @var VehiculoExterno[] */
    private array $vehiculosExternos = [];

    public function __construct(string $nombre, string $direccion, string $telefono)
    {
        parent::__construct($nombre, $direccion, $telefono);
    }

    public function operar(): void
    {
        echo "{$this->nombre}: operando como proveedor de vehículos bajo contrato\n";
    }

    public function proveerVehiculos(int $cantidad): void
    {
        echo "{$this->nombre}: proveyendo {$cantidad} vehículo(s) externo(s)\n";
    }

    public function confirmarDisponibilidad(): bool
    {
        foreach ($this->vehiculosExternos as $vehiculo) {
            if ($vehiculo->obtenerEstado() === 'disponible') {
                return true;
            }
        }
        return false;
    }

    public function agregarVehiculo(VehiculoExterno $vehiculo): void
    {
        $this->vehiculosExternos[] = $vehiculo;
    }

    /** @return VehiculoExterno[] */
    public function getVehiculosExternos(): array { return $this->vehiculosExternos; }
}

// ============================================================
// CONTRATO
// ============================================================

class Contrato
{
    private string        $id;
    private DateTime      $fechaInicio;
    private DateTime      $fechaFin;
    private string        $condiciones;
    private EmpresaAliada $empresaAliada;

    /** @var VehiculoExterno[] */
    private array $vehiculosHabilitados = [];

    public function __construct(
        string        $id,
        DateTime      $fechaInicio,
        DateTime      $fechaFin,
        string        $condiciones,
        EmpresaAliada $empresaAliada
    ) {
        $this->id            = $id;
        $this->fechaInicio   = $fechaInicio;
        $this->fechaFin      = $fechaFin;
        $this->condiciones   = $condiciones;
        $this->empresaAliada = $empresaAliada;
    }

    public function estaVigente(): bool
    {
        $hoy = new DateTime();
        return $hoy >= $this->fechaInicio && $hoy <= $this->fechaFin;
    }

    public function renovar(): void
    {
        echo "Contrato [{$this->id}]: renovado hasta " . $this->fechaFin->format('Y-m-d') . "\n";
    }

    public function habilitarVehiculo(VehiculoExterno $vehiculo): void
    {
        $this->vehiculosHabilitados[] = $vehiculo;
    }

    /** @return VehiculoExterno[] */
    public function obtenerVehiculosDisponibles(): array
    {
        if (!$this->estaVigente()) {
            return [];
        }

        return array_values(
            array_filter(
                $this->vehiculosHabilitados,
                fn(VehiculoExterno $v) => $v->obtenerEstado() === 'disponible'
            )
        );
    }

    public function getId(): string               { return $this->id; }
    public function getEmpresaAliada(): EmpresaAliada { return $this->empresaAliada; }
    public function getCondiciones(): string      { return $this->condiciones; }
}

// ============================================================
// FLOTA
// ============================================================

class Flota
{
    /** @var Vehiculo[] */
    private array $vehiculos = [];

    /** @var VehiculoExterno[] */
    private array $vehiculosExternos = [];

    public function agregarVehiculo(Vehiculo $vehiculo): void
    {
        $this->vehiculos[] = $vehiculo;
    }

    public function agregarVehiculoExterno(VehiculoExterno $vehiculo): void
    {
        $this->vehiculosExternos[] = $vehiculo;
    }

    public function removerVehiculoExterno(VehiculoExterno $vehiculo): void
    {
        $this->vehiculosExternos = array_values(
            array_filter(
                $this->vehiculosExternos,
                fn(VehiculoExterno $v) => $v !== $vehiculo
            )
        );
    }

    /** @return Vehiculo[] */
    public function listarVehiculos(): array { return $this->vehiculos; }

    /** @return VehiculoExterno[] */
    public function listarVehiculosExternos(): array { return $this->vehiculosExternos; }

    public function totalVehiculos(): int
    {
        return count($this->vehiculos) + count($this->vehiculosExternos);
    }
}

// ============================================================
// DEPARTAMENTO DE MANTENIMIENTO
// ============================================================

class DepartamentoMantenimiento
{
    private string $responsable;
    private float  $presupuesto;

    /** @var Vehiculo[] */
    private array $vehiculosEnMantenimiento = [];

    public function __construct(string $responsable, float $presupuesto)
    {
        $this->responsable = $responsable;
        $this->presupuesto = $presupuesto;
    }

    public function programarMantenimiento(Vehiculo $vehiculo): void
    {
        $this->vehiculosEnMantenimiento[] = $vehiculo;
        echo "DepartamentoMantenimiento: mantenimiento programado para [{$vehiculo->getPatente()}]\n";
    }

    public function registrarServicio(Vehiculo $vehiculo): void
    {
        echo "DepartamentoMantenimiento: servicio registrado para [{$vehiculo->getPatente()}]\n";
    }

    public function verificarFuncionamiento(Vehiculo $vehiculo): bool
    {
        echo "DepartamentoMantenimiento: verificando [{$vehiculo->getPatente()}]...\n";
        $vehiculo->getMotor()->diagnosticar();
        $vehiculo->getChasis()->diagnosticar();
        $vehiculo->getCarroceria()->diagnosticar();
        return true;
    }

    public function getResponsable(): string { return $this->responsable; }
    public function getPresupuesto(): float  { return $this->presupuesto; }
}

// ============================================================
// PRODUCTO
// ============================================================

class Producto
{
    private string $id;
    private string $nombre;
    private float  $peso;
    private bool   $requiereRefrigeracion;

    public function __construct(
        string $id,
        string $nombre,
        float  $peso,
        bool   $requiereRefrigeracion = false
    ) {
        $this->id                    = $id;
        $this->nombre                = $nombre;
        $this->peso                  = $peso;
        $this->requiereRefrigeracion = $requiereRefrigeracion;
    }

    public function getId(): string               { return $this->id; }
    public function getNombre(): string           { return $this->nombre; }
    public function getPeso(): float              { return $this->peso; }
    public function requiereRefrigeracion(): bool { return $this->requiereRefrigeracion; }
}

// ============================================================
// CENTRO DE OPERACIONES
// ============================================================

class CentroDeOperaciones
{
    private string                    $nombre;
    private string                    $ubicacion;
    private Flota                     $flota;
    private DepartamentoMantenimiento $departamento;

    /** @var Producto[] */
    private array $productos = [];

    /** @var Contrato[] */
    private array $contratos = [];

    public function __construct(
        string                    $nombre,
        string                    $ubicacion,
        DepartamentoMantenimiento $departamento
    ) {
        $this->nombre       = $nombre;
        $this->ubicacion    = $ubicacion;
        $this->flota        = new Flota();
        $this->departamento = $departamento;
    }

    public function planificarRutas(): void
    {
        echo "{$this->nombre}: planificando rutas para {$this->flota->totalVehiculos()} vehículo(s)\n";
    }

    public function asignarVehiculo(Vehiculo $vehiculo): void
    {
        $this->flota->agregarVehiculo($vehiculo);
        echo "{$this->nombre}: vehículo [{$vehiculo->getPatente()}] asignado a la flota\n";
    }

    public function detectarPicoDemanda(): bool
    {
        echo "{$this->nombre}: evaluando nivel de demanda...\n";
        return true;
    }

    public function solicitarMantenimiento(Vehiculo $vehiculo): void
    {
        $this->departamento->programarMantenimiento($vehiculo);
    }

    public function agregarContrato(Contrato $contrato): void
    {
        $this->contratos[] = $contrato;
    }

    public function almacenarProducto(Producto $producto): void
    {
        $this->productos[] = $producto;
        echo "{$this->nombre}: producto '{$producto->getNombre()}' almacenado\n";
    }

    public function incorporarVehiculosExternos(): void
    {
        foreach ($this->contratos as $contrato) {
            foreach ($contrato->obtenerVehiculosDisponibles() as $vehiculoExterno) {
                $this->flota->agregarVehiculoExterno($vehiculoExterno);
                echo "{$this->nombre}: vehículo externo de '{$vehiculoExterno->getEmpresaOrigen()}'"
                    . " ({$vehiculoExterno->getTipo()->value}) incorporado\n";
            }
        }
    }

    public function getNombre(): string                           { return $this->nombre; }
    public function getUbicacion(): string                        { return $this->ubicacion; }
    public function getFlota(): Flota                             { return $this->flota; }
    public function getDepartamento(): DepartamentoMantenimiento  { return $this->departamento; }
    /** @return Contrato[] */
    public function getContratos(): array                         { return $this->contratos; }
    /** @return Producto[] */
    public function getProductos(): array                         { return $this->productos; }
}

// ============================================================
// GERENCIA LOGISTICA
// ============================================================

class GerenciaLogistica
{
    private string $nombre;
    private string $legajo;

    /** @var CentroDeOperaciones[] */
    private array $centros = [];

    public function __construct(string $nombre, string $legajo)
    {
        $this->nombre = $nombre;
        $this->legajo = $legajo;
    }

    public function agregarCentro(CentroDeOperaciones $centro): void
    {
        $this->centros[] = $centro;
    }

    public function coordinarOperaciones(): void
    {
        foreach ($this->centros as $centro) {
            echo "GerenciaLogistica [{$this->nombre}]: coordinando '{$centro->getNombre()}'\n";
            $centro->planificarRutas();
        }
    }

    public function supervisarEficacia(): void
    {
        $total = 0;
        foreach ($this->centros as $centro) {
            $total += $centro->getFlota()->totalVehiculos();
        }
        echo "GerenciaLogistica [{$this->nombre}]: {$total} vehículo(s) activo(s) en toda la red\n";
    }

    public function getNombre(): string { return $this->nombre; }
    public function getLegajo(): string { return $this->legajo; }
    /** @return CentroDeOperaciones[] */
    public function getCentros(): array { return $this->centros; }
}

// ============================================================
// LOGITRANS
// ============================================================

class LogiTrans extends Empresa
{
    private string            $cuit;
    /** @var string[] */
    private array             $regionesOperacion;
    private GerenciaLogistica $gerencia;

    /** @var CentroDeOperaciones[] */
    private array $centros = [];

    /** @var Contrato[] */
    private array $contratos = [];

    public function __construct(
        string            $nombre,
        string            $direccion,
        string            $telefono,
        string            $cuit,
        array             $regionesOperacion,
        GerenciaLogistica $gerencia
    ) {
        parent::__construct($nombre, $direccion, $telefono);
        $this->cuit              = $cuit;
        $this->regionesOperacion = $regionesOperacion;
        $this->gerencia          = $gerencia;
    }

    public function operar(): void
    {
        echo "{$this->nombre}: operando red logística nacional — "
            . implode(', ', $this->regionesOperacion) . "\n";
    }

    public function agregarCentro(CentroDeOperaciones $centro): void
    {
        $this->centros[] = $centro;
        $this->gerencia->agregarCentro($centro);
    }

    public function firmarContrato(Contrato $contrato): void
    {
        $this->contratos[] = $contrato;
        echo "{$this->nombre}: contrato [{$contrato->getId()}] firmado con"
            . " '{$contrato->getEmpresaAliada()->getNombre()}'\n";
    }

    public function getCuit(): string                   { return $this->cuit; }
    public function getRegionesOperacion(): array        { return $this->regionesOperacion; }
    public function getGerencia(): GerenciaLogistica    { return $this->gerencia; }
    /** @return CentroDeOperaciones[] */
    public function getCentros(): array                 { return $this->centros; }
    /** @return Contrato[] */
    public function getContratos(): array               { return $this->contratos; }
}

// ============================================================
// EJEMPLO DE USO
// ============================================================

echo "=== LogiTrans S.A. — Sistema de Logística ===\n\n";

// -- Empresa
$gerencia   = new GerenciaLogistica('Ana Pérez', 'LEG-001');
$logitrans  = new LogiTrans(
    'LogiTrans S.A.',
    'Av. Logística 1234, Buenos Aires',
    '011-4000-0000',
    '30-12345678-9',
    ['Buenos Aires', 'Córdoba', 'Rosario'],
    $gerencia
);

$logitrans->operar();
echo "\n";

// -- Centro de operaciones
$departamento = new DepartamentoMantenimiento('Carlos Ruiz', 500000.0);
$centro       = new CentroDeOperaciones('Centro Buenos Aires', 'Dock Sud', $departamento);
$logitrans->agregarCentro($centro);

// -- Vehículos propios
$camion       = new CamionCargaPesada('V001', 'AB123CD', 'Mercedes', 20.0, 'plataforma');
$utilitario   = new Utilitario('V002', 'EF456GH', 'Ford Transit', 'CABA', 8.0);
$refrigerado  = new VehiculoRefrigerado('V003', 'IJ789KL', 'Iveco', -18.0, 4.0, 'alimentos');

$centro->asignarVehiculo($camion);
$centro->asignarVehiculo($utilitario);
$centro->asignarVehiculo($refrigerado);
echo "\n";

// -- Entregas (polimorfismo)
echo "--- Realizando entregas ---\n";
foreach ($centro->getFlota()->listarVehiculos() as $vehiculo) {
    $vehiculo->realizarEntrega();
    echo "\n";
}

// -- Mantenimiento
echo "--- Verificación de funcionamiento ---\n";
$centro->solicitarMantenimiento($camion);
$departamento->verificarFuncionamiento($camion);
echo "\n";

// -- Productos
echo "--- Almacenamiento de productos ---\n";
$centro->almacenarProducto(new Producto('P001', 'Electrónica', 5.0));
$centro->almacenarProducto(new Producto('P002', 'Vacunas', 0.5, true));
echo "\n";

// -- Empresa aliada y contrato
echo "--- Acuerdo con empresa aliada ---\n";
$aliada = new EmpresaAliada('TransNorte S.R.L.', 'Ruta 9 Km 50, Tucumán', '0381-400-0000');
$aliada->operar();

$externo1 = new VehiculoExterno('TransNorte S.R.L.', 15.0, TipoVehiculo::CAMION_CARGA_PESADA);
$externo2 = new VehiculoExterno('TransNorte S.R.L.', 6.0,  TipoVehiculo::UTILITARIO);
$aliada->agregarVehiculo($externo1);
$aliada->agregarVehiculo($externo2);

$contrato = new Contrato(
    'CTR-2026-001',
    new DateTime('2026-01-01'),
    new DateTime('2026-12-31'),
    'Hasta 5 unidades por semana, con 48hs de anticipación',
    $aliada
);
$contrato->habilitarVehiculo($externo1);
$contrato->habilitarVehiculo($externo2);

$logitrans->firmarContrato($contrato);
$centro->agregarContrato($contrato);
echo "\n";

// -- Pico de demanda: incorporar externos
echo "--- Pico de demanda detectado ---\n";
if ($centro->detectarPicoDemanda()) {
    $centro->incorporarVehiculosExternos();
}
echo "\n";

// -- Estado final de la flota
echo "--- Estado final de la flota ---\n";
echo "Vehículos propios:\n";
foreach ($centro->getFlota()->listarVehiculos() as $v) {
    echo "  {$v->getMarca()} [{$v->getPatente()}] — capacidad: {$v->obtenerCapacidad()} — estado: {$v->obtenerEstado()}\n";
}
echo "Vehículos externos:\n";
foreach ($centro->getFlota()->listarVehiculosExternos() as $v) {
    echo "  {$v->getEmpresaOrigen()} [{$v->getTipo()->value}] — capacidad: {$v->obtenerCapacidad()} — estado: {$v->obtenerEstado()}\n";
}
echo "\n";

// -- Supervisión gerencial
echo "--- Supervisión gerencial ---\n";
$gerencia->coordinarOperaciones();
$gerencia->supervisarEficacia();
