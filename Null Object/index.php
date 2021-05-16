<?php
interface Plan{

    function getName() : string;
}

interface Customer{

    public function getCurrentPlan() : string;
}

class Free implements Plan{

    public function getName() : string{
        return "Plan Gratuito";
    }
}

class Premium implements Plan{

    public function getName() : string{
        return "Plan Premium";
    }
}

class ActiveCustomer implements Customer{

    protected $plan; 

    public function __construct($plan = null){

        $this->plan = $plan; 
    }

    public function getCurrentPlan() : string{

        return $this->plan->getName();
    }
}

/**
 * Clase generica que sobrescribe el metodo getCurrentPlan
 * para devolver una clase por default "Free" y a su ves 
 * extiende los metodos ActiveCustomer para utilizarlos
 */
class GenericCustomer extends ActiveCustomer{

    public function getCurrentPlan() : string {

        return (new Free())->getName();
    }

    // ... mas metodos nulos que necesites sobrescribir
}

class Order{
    public $customer;
    /** 
     * el magic method invoke permite llamar a una clase como funcion 
     * y en este caso simplemente guarda una instancia de las clases 
     * de tipo Customer (ActiveCustomer / GenericCustomer)
     */  
    public function __invoke(Customer $customer = null){
        $this->customer = $customer;
    }
}

function getPlan(Order $order) : string{
    /**
     * Si customer regresa null instanciamos un objeto generico que tiene
     * las mismas capacidades de un objeto normal (ActiveCostumer) pero
     * con algunso métodos sobrescritos para devolver valores por default
     * 
     * En este caso devuelve un Plan Free si no es ningun tipo de cliente
     */
    $customer = !$order->customer ? new GenericCustomer() : $order->customer; 
    return $customer->getCurrentPlan();
}


$orders = [
    'ord_1' => new Order(),
    'ord_2' => new Order(), 
    'ord_3' => new Order()
];


foreach($orders as $k => $order){
    /**
     * Para efectos del ejemplo incializamos los objetos ord_1 y ord_2 con una isntancia de 
     * active customer y ord_3 sin nigun consumidor asignado
     */
    $k === 'ord_1' || $k === 'ord_2' ? $order(new ActiveCustomer(new Premium)) : $order();
}

foreach($orders as $order){
    echo getPlan($order) . "\n";
    /**
     * Resultado esperado:
     * 
     * Plan Premium
     * Plan Premium
     * Plan Gratuito
     */
}