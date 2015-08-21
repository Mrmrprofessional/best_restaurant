<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Cuisine.php";
    require_once "src/Restaurant.php";

    $server = 'mysql:host=localhost;dbname=food_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CuisineTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Cuisine::deleteAll();
            Restaurant::deleteAll();
        }

        function test_Update() {
            $type = "Pizza";
            $id = null;
            $test_cuisine = new Cuisine($type,$id);
            $test_cuisine->save();

            $new_type = "Chinese";

            $test_cuisine->update($new_type);

            $this->assertEquals("Chinese", $test_cuisine->gettype());
        }

        function test_Delete() {
            $type = "Pizzz";
            $id = null;
            $test_cuisine = new Cuisine($type, $id);
            $test_cuisine->save();

            $type2 = "Chinese";
            $test_cuisine2 = new Cuisine($type2, $id);
            $test_cuisine2->save();

            $test_cuisine->delete();

            $this->assertEquals([$test_cuisine2], Cuisine::getAll());
        }

        function testFindEverything()
        {
            //Arrange
            $type = "Pizza";
            $id = null;
            $test_cuisine = new Cuisine($type, $id);
            $test_cuisine->save();

            $name = "Dominos";
            $address = "4";
            $cuisine_id = $test_cuisine->getId();
            $test_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
            $test_restaurant->save();

            //Act
            $result = Cuisine::findEverything($test_restaurant->getName());

            //Assert
            $this->assertEquals([$test_restaurant], $result);
        }

    }

 ?>
