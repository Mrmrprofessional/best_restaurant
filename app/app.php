<?php


    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Restaurant.php";
    require_once __DIR__."/../src/Cuisine.php";
    require_once __DIR__."/../src/Review.php";

    $app = new Silex\Application();
    $server = 'mysql:host=localhost:8889;dbname=food';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password, $host);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });


    $app->get("/cuisines/{id}", function($id) use ($app) {
        $cuisine = Cuisine::find($id);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });

    $app->get("/cuisines/{id}/edit", function($id) use ($app){
        $cuisine = Cuisine::find($id);
        return $app['twig']->render('cuisine_edit.html.twig', array('cuisine' => $cuisine));
    });

    $app->get("/restaurant/{id}/edit", function($id) use ($app){
        $restaurant = Restaurant::find($id);
        return $app['twig']->render('restaurant_edit.html.twig', array('restaurant' => $restaurant, 'reviews' => $restaurant->getReviews()));
    });

    $app->get('/search', function() use ($app) {
        $search = Cuisine::findEverything($_GET['search']);
        if($search != null){
            if(get_class($search)=="Cuisine")
            {
                return $app['twig']->render('cuisine.html.twig', array('cuisine' => $search, 'restaurants' => $search->getRestaurants()));
            }
            elseif(get_class($search)=="Restaurant")
            {
                return $app['twig']->render('restaurant_edit.html.twig', array('restaurant' => $search, 'reviews' => $search->getReviews()));
            }
        }
        else
        {
            return $app['twig']->render('no_results.html.twig');
        }
    });


    $app->post("/restaurants", function() use ($app) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $cuisine_id = $_POST['cuisine_id'];
        $restaurant = new Restaurant($name, $address, $id = null, $cuisine_id);
        $restaurant->save();
        $cuisine = Cuisine::find($cuisine_id);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getrestaurants()));
    });

    $app->post("/cuisines", function() use ($app) {
        $cuisine = new Cuisine($_POST['type']);
        $cuisine->save();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });

    $app->post('/review', function() use ($app) {
        $user = $_POST['user'];
        $rating = $_POST['rating'];
        $date = $_POST['date'];
        $restaurant_id = $_POST['restaurant_id'];
        $review = new Review($user, $rating, $content = null, $date, $id = null, $restaurant_id);
        $restaurant = Restaurant::find($restaurant_id);
        $review->save();
        return $app['twig']->render('restaurant_edit.html.twig', array('reviews'=> $restaurant->getReviews(), 'restaurant'=>$restaurant));

    });

    $app->patch("/cuisines/{id}", function($id) use ($app) {
        $type = $_POST['type'];
        $cuisine = Cuisine::find($id);
        $cuisine->update($type);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });

    $app->patch("/restaurant/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $restaurant = Restaurant::find($id);
        $restaurant->update($name);
        $cuisine = Cuisine::find($restaurant->getCuisineId());
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });


    $app->delete("/cuisines/{id}", function($id) use ($app) {
        $cuisine = Cuisine::find($id);
        $cuisine->delete();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });

    $app->delete("/restaurant/{id}", function($id) use ($app) {
        $restaurant = Restaurant::find($id);
        $cuisine = Cuisine::find($restaurant->getCuisineId());
        $restaurant->delete();
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });

return $app;

?>
