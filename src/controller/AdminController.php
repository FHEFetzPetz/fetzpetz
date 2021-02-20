<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Address;
use App\FetzPetz\Model\AdministrationAccess;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Order;
use App\FetzPetz\Model\OrderItem;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\ProductCategory;
use App\FetzPetz\Model\User;
use mysql_xdevapi\Exception;

class AdminController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/admin' => 'admin',
            '/admin/users' => 'users',
            '/admin/users/new' => 'usersNew',
            '/admin/users/edit/{id}' => 'usersUpdate',
            '/admin/users/delete/{id}' => 'usersDelete',
            '/admin/categories' => 'categories',
            '/admin/categories/new' => 'categoriesNew',
            '/admin/categories/edit/{id}' => 'categoriesUpdate',
            '/admin/categories/delete/{id}' => 'categoriesDelete',
            '/admin/products' => 'products',
            '/admin/products/new' => 'productsNew',
            '/admin/products/edit/{id}' => 'productsUpdate',
            '/admin/products/delete/{id}' => 'productsDelete',
            '/admin/products/categories/{id}' => 'productsCategories',
            '/admin/products/categories/{id}/new' => 'productsCategoriesNew',
            '/admin/products/categories/{id}/delete/{relId}' => 'productsCategoriesDelete',
            '/admin/orders' => 'orders',
            '/admin/orders/{id}' => 'ordersView',
            '/admin/orders/{id}/status' => 'ordersStatus'
        ];
    }

    public function admin() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');
        return $this->redirectTo('/admin/users');
    }

    public function users() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Users");

        $modelService = $this->kernel->getModelService();

        $users = $modelService->find(User::class);
        $administrators = $modelService->find(AdministrationAccess::class);

        $adminIds = [];

        foreach($administrators as $admin) {
            $adminIds[$admin->user_id] = $admin;
        }

        $this->setParameter('users', $users);
        $this->setParameter('administrators', $adminIds);

        $this->setTemplate('administration');
        $this->setView("admin/users/list.php");
    }

    public function usersNew() {
        return $this->usersUpdate(null);
    }

    public function usersUpdate($id = null) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $type = $id == null ? 'new' : 'update';

        $this->setParameter("title", "FetzPetz | Administration | " . ($type == 'new' ? 'New User' : 'Edit User'));

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $user = $type == 'new' ? null : $modelService->findOneById(User::class, $id);
        if($type == 'update' && $user == null) {
            $notificationService->pushNotification('Unknown User', 'You cannot edit User ' . $id . ' because this user does not exist.', 'warning');
            return $this->redirectTo('/admin/users');
        }

        if($type == 'new') {
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $email = $_POST['email'] ?? '';
            $emailVerified = $_POST['emailVerified'] ?? false;
            $active = $_POST['active'] ?? false;
            $administrator = $_POST['administrator'] ?? false;
        } else {
            $firstName = $_POST['firstName'] ?? $user->firstname;
            $lastName = $_POST['lastName'] ?? $user->lastname;
            $email = $_POST['email'] ?? $user->email;
            $emailVerified = $user->email_verified;
            $active = $user->active;
            $administrator = $user->getAdministrationAccess($modelService) != null;

            $this->setParameter('user', $user);
        }

        $errors = [];

        $this->setParameter('firstName', $firstName);
        $this->setParameter('lastName', $lastName);
        $this->setParameter('email', $email);
        $this->setParameter('emailVerified', $emailVerified);
        $this->setParameter('active', $active);
        $this->setParameter('administrator', $administrator);

        if(isset($_POST['submit'])) {
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeat-password'];

            $emailVerified = isset($_POST['emailVerified']);
            $active = isset($_POST['active']);
            $administrator = isset($_POST['administrator']);

            if($type == 'new' || $email !== $user->email) {
                $existingUser = $this->kernel->getModelService()->findOne(User::class, ['email' => $email]);

                if (!is_null($existingUser))
                    $errors[] = 'Email-Address is already in use!';
            }

            if (!$this->isStringValid($firstName, 2, 100))
                $errors[] = 'First name is invalid!';

            if (!$this->isStringValid($lastName, 2, 50))
                $errors[] = 'Last name is invalid!';

            if($type == 'new' || strlen($password) > 0) {
                if (strlen($password) < 8)
                    $errors[] = 'Password should be at least 8 characters long!';

                if ($password != $repeatPassword)
                    $errors[] = 'Passwords should match!';
            }

            if($type == 'update' && !$administrator) {
                $administratorAccess = $user->getAdministrationAccess($modelService);
                if($administratorAccess != null) $modelService->destroy($administratorAccess);
            }

            if (sizeof($errors) == 0) {
                if($type == 'new') {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                    $user = new User([
                        'firstname' => $firstName,
                        'lastname' => $lastName,
                        'email' => $email,
                        'password_hash' => $passwordHash,
                        'created_at' => new \DateTime(),
                        'email_verified' => $emailVerified,
                        'active' => $active
                    ]);

                    $modelService->insert($user);
                    $notificationService->pushNotification('User created', 'User ' . $user->firstname . ' ' . $user->lastname . ' has been created', 'success');
                } else {
                    $user->firstname = $firstName;
                    $user->lastname = $lastName;
                    $user->email = $email;
                    $user->email_verified = $emailVerified;
                    $user->active = $active;

                    if(strlen($password) > 0)
                        $user->password_hash = password_hash($password, PASSWORD_BCRYPT);

                    $modelService->update($user);
                    $notificationService->pushNotification('User edited', 'User ' . $user->firstname . ' ' . $user->lastname . ' has been edited', 'success');
                }

                if($administrator) {
                    $administratorAccess = new AdministrationAccess([
                        'user_id' => $user->id,
                        'created_by' => $this->getUser()->id,
                        'active' => 1,
                        'created_at' => new \DateTime()
                    ]);

                    $modelService->insert($administratorAccess);
                }

                return $this->redirectTo('/admin/users');
            }
        }

        $this->setParameter('errors', $errors);

        $this->setParameter('type', $type);

        $this->setTemplate('administration');
        $this->setView("admin/users/update.php");
    }

    public function usersDelete($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $user = $modelService->findOneById(User::class, $id);
        if($user == null) {
            $notificationService->pushNotification('Unknown User', 'You cannot delete User ' . $id . ' because this user does not exist.', 'warning');
            return $this->redirectTo('/admin/users');
        }

        if($user->id == $this->getUser()->id) {
            $notificationService->pushNotification('Warning', 'You cannot delete User ' . $id . ' because this is you.', 'warning');
            return $this->redirectTo('/admin/users');
        }

        try {
            $adminAccess = $user->getAdministrationAccess($modelService);
            if($adminAccess != null) $modelService->destroy($adminAccess);
            $modelService->destroy($user, true);
            $notificationService->pushNotification('User deleted', 'User ' . $id . ' has been deleted.', 'success');
        } catch (\PDOException $exception) {
            $notificationService->pushNotification('Error', 'User ' . $id . ' could not be deleted (' . $exception->getMessage() . ')', 'success');
        }

        return $this->redirectTo('/admin/users');
    }


    public function categories() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Categories");

        $modelService = $this->kernel->getModelService();

        $categories = $modelService->find(Category::class);
        $productCategories = $modelService->find(ProductCategory::class);
        $categoryIds = [];
        $productCategoryIds = [];

        foreach($categories as $category) $categoryIds[$category->id] = $category;
        foreach($productCategories as $productCategory) {
            $categoryId = $productCategory->category_id;

            if(!isset($productCategoryIds[$categoryId]))
                $productCategoryIds[$categoryId] = [$productCategory];
            else
                $productCategoryIds[$categoryId][] = $productCategory;
        }

        foreach($categories as $category) {
            if(!is_null($category->parent)) $category->parent_object = $categoryIds[$category->parent] ?? null;
            $category->product_count = sizeof($productCategoryIds[$category->id] ?? []);
        }

        $this->setParameter('categories', $categories);

        $this->setTemplate('administration');
        $this->setView("admin/categories/list.php");
    }

    public function categoriesNew() {
        return $this->categoriesUpdate(null);
    }
    public function categoriesUpdate($id = null) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $type = $id == null ? 'new' : 'update';

        $this->setParameter("title", "FetzPetz | Administration | " . ($type == 'new' ? 'New Category' : 'Edit Category'));

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $category = $type == 'new' ? null : $modelService->findOneById(Category::class, $id);
        if($type == 'update' && $category == null) {
            $notificationService->pushNotification('Unknown Category', 'You cannot edit Category ' . $id . ' because this category does not exist.', 'warning');
            return $this->redirectTo('/admin/categories');
        }

        if($type == 'new') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $active = $_POST['active'] ?? false;
            $parent = $_POST['parent'] ?? -1;
        } else {
            $name = $_POST['name'] ?? $category->name;
            $description = $_POST['description'] ?? $category->description;
            $active = $category->active;
            $parent = $_POST['parent'] ?? ($category->parent ?? -1);

            $this->setParameter('category', $category);
        }

        $errors = [];

        $this->setParameter('name', $name);
        $this->setParameter('description', $description);
        $this->setParameter('active', $active);
        $this->setParameter('parent', $parent);

        $parentCategories = $modelService->find(Category::class, ['parent' => null]);
        $parentCategoryIds = [];

        foreach($parentCategories as $parentCategory)
            $parentCategoryIds[$parentCategory->id] = $parentCategory;

        $this->setParameter('parentCategories', $parentCategoryIds);

        if(isset($_POST['submit'])) {

            $active = isset($_POST['active']);
            $this->setParameter('active', $active);

            if (!$this->isStringValid($name, 2, 100))
                $errors[] = 'Name is invalid!';

            if (!$this->isStringValid($description, 2, 2000))
                $errors[] = 'Description is invalid!';

            if (sizeof($errors) == 0) {
                if($type == 'new') {
                    $category = new Category([
                        'name' => $name,
                        'description' => $description,
                        'active' => $active,
                        'parent' => $parent == -1 ? null : $parent,
                        'created_by' => $this->getUser()->id,
                    ]);

                    $modelService->insert($category);
                    $notificationService->pushNotification('Category created', 'Category ' . $category->name . ' has been created', 'success');
                } else {
                    $category->name = $name;
                    $category->description = $description;
                    $category->active = $active;
                    $category->parent = $parent == -1 ? null : $parent;

                    $modelService->update($category);
                    $notificationService->pushNotification('Category edited', 'Category ' . $category->name . ' has been edited', 'success');
                }

                return $this->redirectTo('/admin/categories');
            }
        }

        $this->setParameter('errors', $errors);

        $this->setParameter('type', $type);

        $this->setTemplate('administration');
        $this->setView("admin/categories/update.php");
    }

    public function categoriesDelete($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $category = $modelService->findOneById(Category::class, $id);
        if($category == null) {
            $notificationService->pushNotification('Unknown Category', 'You cannot delete Category ' . $id . ' because this category does not exist.', 'warning');
            return $this->redirectTo('/admin/categories');
        }

        try {
            $productCategories = $modelService->find(ProductCategory::class, ['category_id' => $id]);
            foreach($productCategories as $productCategory) $modelService->destroy($productCategory);

            $modelService->destroy($category, true);
            $notificationService->pushNotification('Category deleted', 'Category ' . $id . ' has been deleted.', 'success');
        } catch (\PDOException $exception) {
            $notificationService->pushNotification('Error', 'Category ' . $id . ' could not be deleted (' . $exception->getMessage() . ')', 'success');
        }

        return $this->redirectTo('/admin/categories');
    }

    public function products() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Products");

        $modelService = $this->kernel->getModelService();

        $products = $modelService->find(Product::class);
        $productCategories = $modelService->find(ProductCategory::class);
        $categories = $modelService->find(Category::class);

        $categoryIds = [];
        $productCategoryIds = [];

        foreach ($categories as $category) $categoryIds[$category->id] = $category;

        foreach($productCategories as $productCategory) {
            $productId = $productCategory->product_id;
            $categoryId = $productCategory->category_id;

            if(!isset($productCategoryIds[$productId]))
                $productCategoryIds[$productId] = [$categoryId];
            else
                $productCategoryIds[$productId][] = $categoryId;
        }

        foreach($products as $product) {
            $product->categories = $productCategoryIds[$product->id] ?? [];
        }

        $this->setParameter('products', $products);
        $this->setParameter('categoryIds', $categoryIds);

        $this->setTemplate('administration');
        $this->setView("admin/products/list.php");
    }

    public function productsNew() {
        return $this->productsUpdate(null);
    }

    public function productsUpdate($id = null) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $type = $id == null ? 'new' : 'update';

        $this->setParameter("title", "FetzPetz | Administration | " . ($type == 'new' ? 'New Product' : 'Edit Product'));

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $product = $type == 'new' ? null : $modelService->findOneById(Product::class, $id);
        if($type == 'update' && $product == null) {
            $notificationService->pushNotification('Unknown Product', 'You cannot edit Product ' . $id . ' because this product does not exist.', 'warning');
            return $this->redirectTo('/admin/products');
        }

        if($type == 'new') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $costPerItem = $_POST['costPerItem'] ?? '';
            $active = $_POST['active'] ?? false;
        } else {
            $name = $_POST['name'] ?? $product->name;
            $description = $_POST['description'] ?? $product->description;
            $costPerItem = $_POST['costPerItem'] ?? $product->cost_per_item;
            $active = $product->active;

            $this->setParameter('product', $product);
        }

        $errors = [];

        $this->setParameter('name', $name);
        $this->setParameter('description', $description);
        $this->setParameter('costPerItem', $costPerItem);
        $this->setParameter('active', $active);

        if(isset($_POST['submit'])) {
            $active = isset($_POST['active']);
            $this->setParameter('active', $active);

            if (!$this->isStringValid($name, 2, 100))
                $errors[] = 'Name is invalid!';

            if (!$this->isStringValid($description, 2, 2000))
                $errors[] = 'Description is invalid!';

            $costPerItem = floatval(str_replace(',', '.', $costPerItem));
            $imagePath = $type == 'update' ? $product->image : null;

            if($type == 'new' || isset($_FILES['image']) && file_exists($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $image = $_FILES['image'];
                $imageValid = true;
                $appDir = $this->getConfig()['appDir'];

                if($image['size'] > $this->getConfig()['imageUploadSize']) {
                    $errors[] = 'Uploaded file is too large';
                    $imageValid = false;
                }

                if(exif_imagetype($image['tmp_name']) === FALSE) {
                    $errors[] = 'Uploaded file is invalid';
                    $imageValid = false;
                }

                if(!$imageValid)
                    unlink($image['tmp_name']);
                else {
                    $extension = explode('.', $image['name']);
                    $fileName = uniqid(date('dmY-'));
                    if(sizeof($extension) > 1) $fileName .= '.' . end($extension);

                    $imagePath = '/assets/upload/' . $fileName;

                    if(!move_uploaded_file($image['tmp_name'], $appDir . str_replace('/', DIRECTORY_SEPARATOR, $imagePath))) {
                        $errors[] = 'Uploaded file could not be saved';
                        unlink($image['tmp_name']);
                    }
                }

                if($type == 'update') unlink($appDir . str_replace('/', DIRECTORY_SEPARATOR, $product->image));
            }

            if (sizeof($errors) == 0) {
                if($type == 'new') {
                    $product = new Product([
                        'name' => $name,
                        'description' => $description,
                        'active' => $active,
                        'image' => $imagePath,
                        'cost_per_item' => $costPerItem,
                        'created_by' => $this->getUser()->id
                    ]);

                    $modelService->insert($product);
                    $notificationService->pushNotification('Product created', 'Product ' . $product->name . ' has been created', 'success');
                } else {
                    $product->name = $name;
                    $product->description = $description;
                    $product->active = $active;
                    $product->image = $imagePath;
                    $product->cost_per_item = $costPerItem;

                    $modelService->update($product);
                    $notificationService->pushNotification('Product edited', 'Product ' . $product->name . ' has been edited', 'success');
                }

                return $this->redirectTo('/admin/products');
            }
        }

        $this->setParameter('errors', $errors);

        $this->setParameter('type', $type);

        $this->setTemplate('administration');
        $this->setView("admin/products/update.php");
    }

    public function productsDelete($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $product = $modelService->findOneById(Product::class, $id);
        if($product == null) {
            $notificationService->pushNotification('Unknown Product', 'You cannot delete Product ' . $id . ' because this product does not exist.', 'warning');
            return $this->redirectTo('/admin/products');
        }

        try {
            $productCategories = $modelService->find(ProductCategory::class, ['product_id' => $id]);
            foreach($productCategories as $productCategory) $modelService->destroy($productCategory);
            unlink($this->getConfig()['appDir'] . str_replace('/', DIRECTORY_SEPARATOR, $product->image));

            $modelService->destroy($product, true);
            $notificationService->pushNotification('Product deleted', 'Product ' . $id . ' has been deleted.', 'success');
        } catch (\PDOException $exception) {
            $notificationService->pushNotification('Error', 'Product ' . $id . ' could not be deleted (' . $exception->getMessage() . ')', 'success');
        }

        return $this->redirectTo('/admin/products');
    }

    public function productsCategories($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $product = $modelService->findOneById(Product::class, $id);
        if($product == null) {
            $notificationService->pushNotification('Unknown Product', 'You cannot view Product ' . $id . ' because this product does not exist.', 'warning');
            return $this->redirectTo('/admin/products');
        }

        $this->setParameter("title", "FetzPetz | Administration | Products");

        $productCategories = $modelService->find(ProductCategory::class, ['product_id' => $product->id]);
        $categoryIds = [];

        foreach($productCategories as $productCategory)
            $categoryIds[$productCategory->category_id] = null;

        $categories = $modelService->find(Category::class, ['id' => array_keys($categoryIds)]);
        foreach($categories as $category)
            $categoryIds[$category->id] = $category;

        foreach($productCategories as $productCategory)
            $productCategory->category = $categoryIds[$productCategory->category_id];

        $this->setParameter('categories', $productCategories);
        $this->setParameter('product', $product);

        $this->setTemplate('administration');
        $this->setView("admin/products/categories.php");
    }

    public function productsCategoriesNew($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $product = $modelService->findOneById(Product::class, $id);
        if($product == null) {
            $notificationService->pushNotification('Unknown Product', 'You cannot view Product ' . $id . ' because this product does not exist.', 'warning');
            return $this->redirectTo('/admin/products');
        }

        $this->setParameter("title", "FetzPetz | Administration | Products");

        $this->setParameter('product', $product);

        $existingRelations = $modelService->find(ProductCategory::class, ['product_id' => $product->id]);
        $categoryIds = [];

        foreach($existingRelations as $existingRelation) $categoryIds[] = $existingRelation->category_id;

        if(isset($_POST['submit'])) {
            $newCategoryIds = [];
            foreach($_POST as $key=>$value)
                if(strpos($key, 'category-') !== false) $newCategoryIds[] = str_replace('category-', '', $key);

            $newRelations = 0;

            foreach($newCategoryIds as $categoryId) {
                if(!in_array($categoryId, $categoryIds)) {
                    $productCategory = new ProductCategory([
                        'product_id' => $id,
                        'category_id' => $categoryId
                    ]);

                    $modelService->insert($productCategory);
                    $newRelations++;
                }
            }

            $notificationService->pushNotification('Created Relations', $newRelations . ' new relation(s) has been created for product ' . $id, 'success');
            return $this->redirectTo('/admin/products/categories/' . $id);
        }

        $categories = $modelService->find(Category::class);

        $availableCategories = [];

        foreach($categories as $category) if(!in_array($category->id, $categoryIds)) $availableCategories[] = $category;

        $this->setParameter('categories', $availableCategories);

        $this->setTemplate('administration');
        $this->setView("admin/products/categoriesNew.php");
    }

    public function productsCategoriesDelete($id, $relId) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $productCategory = $modelService->findOne(ProductCategory::class, ['product_id' => $id, 'id' => $relId]);
        if($productCategory == null) {
            $notificationService->pushNotification('Unknown Relation', 'You cannot delete Relation ' . $id . ' because this relation does not exist.', 'warning');
            return $this->redirectTo('/admin/products/categories/' . $id);
        }

        $modelService->destroy($productCategory);
        $notificationService->pushNotification('Deleted Relation', 'The Relation ' . $id . ' has been deleted.', 'success');

        return $this->redirectTo('/admin/products/categories/' . $id);
    }

    public function orders() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Orders");

        $modelService = $this->kernel->getModelService();

        $orders = $modelService->find(Order::class);
        $orderItems = $modelService->find(OrderItem::class);
        $addresses = $modelService->find(Address::class);

        $orderItemIds = [];
        $addressIds = [];

        foreach($orderItems as $orderItem) {
            $orderId = $orderItem->order_id;

            if(!isset($orderItemIds[$orderId]))
                $orderItemIds[$orderId] = [$orderItem];
            else
                $orderItemIds[$orderId][] = $orderItem;
        }
        foreach($addresses as $address) $addressIds[$address->id] = $address;

        foreach($orders as $order) {
            $order->shipping_address = $addressIds[$order->shipping_address_id] ?? null;
            $order->order_items = $orderItemIds[$order->id] ?? [];
            $total = 0;

            foreach($order->order_items as $orderItem) {
                $total += $orderItem->amount * $orderItem->cost_per_item;
            }

            $order->total = $total;
        }

        $this->setParameter('orders', $orders);

        $this->setTemplate('administration');
        $this->setView("admin/orders/list.php");
    }

    public function ordersView($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Orders");

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $order = $modelService->findOneById(Order::class, $id);

        if($order == null) {
            $notificationService->pushNotification('Unknown Order', 'You cannot view Order ' . $id . ' because this order does not exist.', 'warning');
            return $this->redirectTo('/admin/orders');
        }

        $this->setParameter('order', $order);

        $this->setTemplate('administration');
        $this->setView("admin/orders/details.php");
    }

    public function ordersStatus($id) {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration | Orders");

        $modelService = $this->kernel->getModelService();
        $notificationService = $this->kernel->getNotificationService();

        $order = $modelService->findOneById(Order::class, $id);

        if($order == null) {
            $notificationService->pushNotification('Unknown Order', 'You cannot view Order ' . $id . ' because this order does not exist.', 'warning');
            return $this->redirectTo('/admin/orders');
        }

        $status = $_POST['status'] ?? $order->order_status;

        $this->setParameter('order', $order);
        $this->setParameter('status', $status);

        if(isset($_POST['submit'])) {
            $order->order_status = $status;
            $modelService->update($order);

            $notificationService->pushNotification('Status Updated', 'Updated order status for ' . $id . ' to ' . $status, 'success');
            return $this->redirectTo('/admin/orders/' . $id);
        }

        $this->setTemplate('administration');
        $this->setView("admin/orders/status.php");
    }

    private function isStringValid(string $value, int $min, int $max): bool
    {
        $length = strlen($value);

        if ($length < $min) return false;
        if ($length > $max) return false;
        return true;
    }
}