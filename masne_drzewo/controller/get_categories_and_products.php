<?php
require_once '../config/database.php';

try {
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

    switch ($sort) {
        case 'name_asc':
            $orderByCategory = 'name ASC';
            $orderByProduct = 'p.name ASC';
            break;
        case 'name_desc':
            $orderByCategory = 'name DESC';
            $orderByProduct = 'p.name DESC';
            break;
        case 'date_asc':
            $orderByCategory = 'date_add ASC';
            $orderByProduct = 'p.date_add ASC';
            break;
        case 'date_desc':
            $orderByCategory = 'date_add DESC';
            $orderByProduct = 'p.date_add DESC';
            break;
        default:
            $orderByCategory = 'name ASC';
            $orderByProduct = 'p.name ASC';
            break;
    }

    $queryCategories = $db->prepare("SELECT id_category, name, id_parent FROM category ORDER BY $orderByCategory");
    $queryCategories->execute();
    $categories = $queryCategories->fetchAll(PDO::FETCH_ASSOC);

    $queryProducts = $db->prepare("SELECT p.id_product, p.name, cp.id_category FROM products p 
                                    JOIN category_products cp ON p.id_product = cp.id_product 
                                    ORDER BY $orderByProduct");
    $queryProducts->execute();
    $products = $queryProducts->fetchAll(PDO::FETCH_ASSOC);

    $productGroup = [];
    foreach ($products as $product) {
        $productGroup[$product['id_category']][] = $product;
    }

    echo json_encode(['categories' => $categories, 'products' => $productGroup]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
