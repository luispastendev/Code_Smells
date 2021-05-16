<?php

interface Plan{
    function getName() : string;
}

interface ActiveCustomer{
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

class Customer implements ActiveCustomer{

    protected $plan; 

    public function __construct($plan){

        $this->plan = $plan; 
    }

    public function getCurrentPlan() : string{
        return $this->plan->getName();
    }
}

class Order{
    public $customer;

    public function __invoke($customer = null){
        $this->customer = $customer;
    }
}

function getPlan(Order $order) : string{
    // SET PLAN
    if($order->customer === null){
        $plan = (new Free())->getName();
    }else{
        $plan = $order->customer->getCurrentPlan();
    }
    return $plan;
}


$orders = [
    'ord_1' => new Order(),
    'ord_2' => new Order(), 
    'ord_3' => new Order()
];


foreach($orders as $k => $order){
    $k === 'ord_1' || $k === 'ord_2' ? $order(new Customer(new Premium)) : $order();
}
// var_dump($orders);

foreach($orders as $order){
    echo getPlan($order) . "\n";
}