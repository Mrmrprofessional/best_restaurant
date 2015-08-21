<?php
    class Restaurant
    {
        private $name;
        private $address;
        private $cuisine_id;
        private $id;


        function __construct($name, $address, $id = null, $cuisine_id)
        {
            $this->name = $name;
            $this->address = $address;
            $this->id = $id;
            $this->cuisine_id = $cuisine_id;
        }

        function setname($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getAddress()
        {
            return $this->address;
        }

        function getId()
        {
            return $this->id;
        }

        function getCuisineId()
        {
            return $this->cuisine_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->exec("INSERT INTO restaurants (name, address, cuisine_id) VALUES ('{$this->getName()}', '{$this->getAddress()}', {$this->getCuisineId()})");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants ORDER BY address;");
            $restaurants = array();
            foreach($returned_restaurants as $restaurant) {
                $name = $restaurant['name'];
                $address = $restaurant['address'];
                $id = $restaurant['id'];
                $cuisine_id = $restaurant['cuisine_id'];
                $new_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
                array_push($restaurants, $new_restaurant);
            }
            return $restaurants;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM restaurants;");
        }

        // static function deleterestaurants($cuisine_id)
        // {
        //   $GLOBALS['DB']->exec("DELETE FROM restaurants WHERE cuisine_id = {$cuisine_id};");
        // }

        static function find($search_id)
        {
            $found_restaurant = null;
            $restaurants = Restaurant::getAll();
            foreach($restaurants as $restaurant) {
                $restaurant_id = $restaurant->getId();
                if ($restaurant_id == $search_id) {
                  $found_restaurant = $restaurant;
                }
            }
            return $found_restaurant;
        }

        // static function findCuisineId($search_id)
        // {
        //     $found_cuisine = null;
        //     $restaurants = Restaurant::getAll();
        //     foreach($restaurants as $restaurant) {
        //         $restaurant_id = $restaurant->getId();
        //         $cuisine_search = $restaurant->getCuisineId();
        //         if ($restaurant_id == $search_id) {
        //           $found_cuisine = $cuisine_search;
        //         }
        //     }
        //     return $found_cuisine;
        // }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE restaurants SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete() {
            $GLOBALS['DB']->exec("DELETE FROM restaurants WHERE id = {$this->getId()};");
        }

        function getReviews()
        {
            $reviews = array();
            $returned_reviews = $GLOBALS['DB']->query("SELECT * FROM reviews WHERE restaurant_id = {$this->getId()} ORDER BY date;");
            foreach($returned_reviews as $review) {
                $user = $review['user'];
                $date = $review['date'];
                $rating = $review['rating'];
                $id = $review['id'];
                $content = $review['content'];
                $restaurant_id = $review['restaurant_id'];
                $new_review = new Review($user, $rating, $content, $date, $id, $restaurant_id);
                array_push($reviews, $new_review);
            }
            return $reviews;
        }
    }
?>
