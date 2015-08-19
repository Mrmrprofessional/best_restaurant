<?php
    class Cuisine
    {
        private $type;
        private $id;

        function __construct($type, $id = null)
        {
            $this->type = $type;
            $this->id = $id;
        }

        function settype($new_type)
        {
            $this->type = (string) $new_type;
        }

        function gettype()
        {
            return $this->type;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO cuisines (type) VALUES ('{$this->gettype()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
            // $this->setId($result_id);
        }

        static function getAll()
        {
            $returned_cuisines = $GLOBALS['DB']->query("SELECT * FROM cuisines;");
            $cuisines = array();
            foreach($returned_cuisines as $cuisine) {
                $type = $cuisine['type'];
                $id = $cuisine['id'];
                $new_cuisine = new Cuisine($type, $id);
                array_push($cuisines, $new_cuisine);
            }
            return $cuisines;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM cuisines;");
        }

        // static function deleteEverything()
        // {
        //   $GLOBALS['DB']->exec("DELETE FROM cuisines; DELETE FROM cuisines;");
        // }

        static function find($search_id)
        {
            $found_cuisine = null;
            $cuisines = Cuisine::getAll();
            foreach($cuisines as $cuisine) {
                $cuisine_id = $cuisine->getId();
                if ($cuisine_id == $search_id) {
                  $found_cuisine = $cuisine;
                }
            }
            return $found_cuisine;
        }

        // static function getMatches($cuisine_input)
        // {
        //     $returned_cuisines = $GLOBALS['DB']->query("SELECT * FROM cuisines WHERE type LIKE '%$cuisine_input%';");
        //     $cuisines = array();
        //     foreach($returned_cuisines as $cuisine) {
        //         $type = $cuisine['type'];
        //         $id = $cuisine['id'];
        //         $new_cuisine = new cuisine($type, $id);
        //         array_push($cuisines, $new_cuisine);
        //     }
        //     return $cuisines;
        // }

        function getRestaurants()
        {
            $restaurants = Array();
            $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants WHERE cuisine_id = {$this->getId()} ORDER BY name;");
            foreach($returned_restaurants as $restaurant) {
                $name = $restaurant['name'];
                $address = $restaurant['address'];
                $cuisine_id = $restaurant['cuisine_id'];
                $id = $restaurant['id'];
                $new_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
                array_push($restaurants, $new_restaurant);
            }
            return $restaurants;
        }

        function update($new_type) {
            $GLOBALS['DB']->exec("UPDATE cuisines SET type = '{$new_type}' WHERE id = {$this->getId()};");
            $this->setType($new_type);
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM cuisines WHERE id = {$this->getId()}; DELETE FROM restaurants WHERE cuisine_id = {$this->getId()};");
        }

        static function findCuisine($search_type)
        {
            $found_cuisine = null;
            $cuisines = Cuisine::getAll();
            foreach($cuisines as $cuisine) {
                if($cuisine->gettype() == $search_type) {
                    $found_cuisine = $cuisine;
                }
            }
            return $found_cuisine;
        }


    }


?>
