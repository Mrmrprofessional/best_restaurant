<?php
    class Review
    {
        private $user;
        private $rating;
        private $content;
        private $id;
        private $date;
        private $restaurant_id;

        function __construct($user, $rating, $content = null, $date, $id = null, $restaurant_id)
        {
            $this->user = $user;
            $this->rating = $rating;
            $this->content = $content;
            $this->date = $date;
            $this->id = $id;
            $this->restaurant_id = $restaurant_id;
        }

        function getUser() {
            return $this->user;
        }

        function getRating() {
            return $this->rating;
        }

        function getContent() {
            return $this->content;
        }

        function getDate() {
            return $this->date;
        }

        function getId() {
            return $this->id;
        }

        function getRestaurantId() {
            return $this->restaurant_id;
        }


        function save() {
            $statement = $GLOBALS['DB']->exec("INSERT INTO reviews (user, rating, content, date, restaurant_id) VALUES ('{$this->getUser()}', {$this->getRating()}, '{$this->getContent()}', '{$this->getDate()}', {$this->getRestaurantId()})");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {

            $returned_reviews = $GLOBALS['DB']->query("SELECT * FROM reviews ORDER BY date;");
            $reviews = array();
            foreach($returned_reviews as $review) {
                $user = $review['user'];
                $rating = $review['rating'];
                $content = $review['content'];
                $date = $review['date'];
                $id = $rewview['id'];
                $restaurant_id = $review['restaurant_id'];
                $new_review = new Review($user, $rating, $content, $date, $id, $restaurant_id);
                array_push($reviews, $new_review);
            }
            return $reviews;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM reviews;");
        }

    }

 ?>
