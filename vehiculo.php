<?php
//interface define los métodos que deben implementar las clases que la implementen
interface IVehiculo {
    public function realizarEntrega(): void;
    public function obtenerEstado(): string;
    public function obtenerCapacidad(): float;        
}

//clase padre abstracta de componentes
abstract class Componente {
    protected string $codigo;
    protected string $estado;

    public function __construct(string $codigo, string $estado) {
        $this->codigo = $codigo;
        $this->estado = $estado;
    }
    abstract public function diagnosticar(): void;
}
//clases hijas de componentes
class Motor extends Componente {
    private float $potencia;
    public function __construct(string $codigo, string $estado, float $potencia) {
        parent::__construct($codigo, $estado); // Llamada al constructor de la clase padre
        $this->potencia = $potencia;
    }
    public function diagnosticar(): void {
        echo "Diagnóstico del motor: Código: $this->codigo, Estado: $this->estado, Potencia: $this->potencia HP\n";
    }
}
class Chasis extends Componente {
    private string $tipo;
    public function __construct(string $codigo, string $estado, string $tipo) {
        parent::__construct($codigo, $estado); // Llamada al constructor de la clase padre
        $this->tipo = $tipo;
    }
    public function diagnosticar(): void {
        echo "Diagnóstico del chasis: Código: $this->codigo, Estado: $this->estado, Tipo: $this->tipo\n";
    }
}
class Carroceria extends Componente {
    private string $material;
    public function __construct(string $codigo, string $estado, string $material) {
        parent::__construct($codigo, $estado); // Llamada al constructor de la clase padre
        $this->material = $material;
    }
    public function diagnosticar(): void {
        echo "Diagnóstico de la carrocería: Código: $this->codigo, Estado: $this->estado, Material: $this->material\n";
    }
}
    //classe abstracta vehiculo que implementa la interface IVehiculo
    abstract class vehiculo implements IVehiculo {
        //atributos comunes a todos los vehículos
        protected string $marca;
        protected string $id;
        protected string $patente;
        protected string $estado;
        //atributos especificos de cada vehiculo
        protected Motor $motor;
        protected Chasis $chasis;
        protected Carroceria $carroceria;

    public function __construct(string $marca, string $id, string $patente, string $estado, Motor $motor, Chasis $chasis, Carroceria $carroceria) { //constructor con los parámetros comunes a todos los vehículos
        $this->marca = $marca;
        $this->id = $id;
        $this->patente = $patente;
        $this->estado = $estado;
        $this->motor = $motor;
        $this->chasis = $chasis;
        $this->carroceria = $carroceria;
    }
    abstract public function realizarEntrega(): void; //es abstracto y todas las subclases deben implementarlo

    public function obtenerEstado(): string {
        return $this->estado;
    }

    public function obtenerCapacidad(): float {
        return 0.0; // Método base, puede ser sobrescrito por las clases hijas
    }
}
//subclases de los vehiculos heredan de vehiculo
class camionCargaPesada extends vehiculo {
    private float $capacidadTon;
    private string $tipoRemolque;

    //1-constructor de los 7 parámetros obligatorios + los 2 específicos de esta clase
    public function __construct( string $marca, string $id, string $patente, string $estado, Motor $motor, Chasis $chasis, Carroceria $carroceria, float $capacidadTon, string $tipoRemolque
    ) {
        //2-le doy 7 parámetros obligatorios al constructor de vehiculo
        parent::__construct($marca, $id, $patente, $estado, $motor, $chasis, $carroceria);
        // guardo los 2 datos específicos de esta subclase
        $this->capacidadTon = $capacidadTon;
        $this->tipoRemolque = $tipoRemolque;
    }
    //3-implemento el método abstracto de la clase padre
    public function cargarMercancia(): void {
        echo "Cargando mercancía en el camión de carga pesada...\n";
    }

    public function realizarEntrega(): void {
        $this->cargarMercancia();
        echo "Realizando entrega con el camión de carga pesada...\n";
    }
    public function obtenerCapacidad(): float {
        return $this->capacidadTon;
    }
}
class utilitario extends vehiculo {
    private string $zonaCobertura;
    private float $capacidadM3;
    //1-constructor de los 7 parámetros obligatorios + los 2 específicos de esta clase
    public function __construct(
        string $marca,
        string $id,
        string $patente,
        string $estado,
        Motor $motor,
        Chasis $chasis,
        Carroceria $carroceria,
        string $zonaCobertura,
        float $capacidadM3
    ) {
        parent::__construct($marca, $id, $patente, $estado, $motor, $chasis, $carroceria); //2- Llamada al constructor de la clase padre
        $this->zonaCobertura = $zonaCobertura;
        $this->capacidadM3 = $capacidadM3;
    }
    //3-implemento el método abstracto de la clase padre
    public function obtenerCapacidad(): float {
    return $this->capacidadM3;
    }
    public function obtenerZona(): string {
        return $this->zonaCobertura;
    }
    public function realizarEntrega(): void {
        echo "Realizando entrega con el utilitario...\n";
    }

    public function navegarZonaUrbana(): void {
        echo "Navegando por la zona urbana con el utilitario...\n";
    }
}
class vehiculoRefrigerado extends vehiculo {
    private float $tempMin;
    private float $tempMax;
    private string $tipoCarga;
    //constructor de los 7 parámetros obligatorios + los 3 específicos de esta clase
    public function __construct(
        string $marca,
        string $id,
        string $patente,
        string $estado,
        Motor $motor,
        Chasis $chasis,
        Carroceria $carroceria,
        float $tempMin,
        float $tempMax,
        string $tipoCarga
    ) {
        parent::__construct($marca, $id, $patente, $estado, $motor, $chasis, $carroceria);// 2-Llamada al constructor de la clase padre
        $this->tempMin = $tempMin;
        $this->tempMax = $tempMax;
        $this->tipoCarga = $tipoCarga;
    }
    //3-implemento el método abstracto de la clase padre
    public function obtenerCapacidad(): float {
        
        return 10.0; // Capacidad en metros cúbicos
    }
    public function realizarEntrega(): void {
        echo "Realizando entrega con el vehículo refrigerado...\n";
    }
    public function controlarTemperatura(): void {
        echo "Controlando la temperatura en el vehículo refrigerado...\n";
    }
    public function verificarCadenaDeFrio(): void {
        echo "Verificando la cadena de frío en el vehículo refrigerado...\n";
    }
}
//clase vehiculoExterno que implementa directamente de la interface IVehiculo
class vehiculoExterno implements IVehiculo {
    private string $idContrato;
    private string $empresaOrigen;
    private DateTime $fechaInicio;
    private DateTime $fechaFin;
    private float $capacidad;
    //constructor con los parámetros del diagrama
    public function __construct(string $idContrato, string $empresaOrigen, string $fechaInicio, string $fechaFin, float $capacidad) {//constructor con los parámetros del diagrama
        $this->idContrato = $idContrato;
        $this->empresaOrigen = $empresaOrigen;
        $this->fechaInicio = new DateTime($fechaInicio);
        $this->fechaFin = new DateTime($fechaFin);
        $this->capacidad = $capacidad;
    }
    //implemento los métodos de la interface IVehiculo
    public function realizarEntrega(): void {
        echo "Realizando entrega con el vehículo externo...\n";
    }

    public function obtenerEstado(): string {
        return "Estado del vehículo externo: En tránsito";
    }

    public function obtenerCapacidad(): float {
        return 0.0; // no tiene una capacidad específica
    }
}