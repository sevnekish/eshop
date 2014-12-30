<?php
///////////////////////////////////OTHER//////////////////////////////////////////////

//функция очищающая строку от пробелов и html тегов
	function cleanStr($str)
	{
		return trim(strip_tags($str));
	}

//функция очищающая строку от пробелов и html тегов
//+ экранирование знаков за счёт специального представления строки
	function cleanMysqlStr($str)
	{
		global $link;
		return mysqli_real_escape_string($link, cleanStr($str));
	}

//функия фильтрации int значений (по модулю + приведение к int)
	function cleanInt($int)
	{
		return abs((int)$int);
	}

//функция определения написания баллов/балла/балл
	function ballCheck($ball)
	{
		if($ball == '5')
			echo 'баллов';
		elseif($ball == '1')
			echo 'балл';
		else
			echo 'балла';
	}

//функция определния по id значение title и заголовка для content, возвращает array со значениями
	function titleHeaderSet()
	{
		$title = 'eshop - смартфоны по низким ценам';
		$headerCont = 'Добро пожаловать на сайт! Наши цены самые низкие!';
		$id = strtolower(cleanStr($_GET['id']));

		switch($id)
		{
			case 'contacts':
				$title = 'Контакты';
				$headerCont = 'Наши контакты';
				break;
			case 'catalog':
				$title = 'Каталог смартфонов';
				$headerCont = 'Смартфоны';
				break;
			case 'reviews':
				$title = 'Отзывы';
				$headerCont = 'Отзывы наших клиентов';
				break;
			case 'admin':
				$title = 'Администрирование';
				$headerCont = 'Администрирование';
				break;
			case 'additem':
				$title = 'Добавление товара';
				$headerCont = 'Добавление товара';
				break;
			case 'adduser':
				$title = 'Добавление пользователя';
				$headerCont = 'Добавление пользователя';
				break;
			case 'orders':
				$title = 'Заказы';
				$headerCont = 'Заказы';
				break;
			case 'basket':
				$title = 'Корзина';
				$headerCont = 'Корзина';
				break;
			case 'orderform':
				$title = 'Оформление заказа';
				$headerCont = 'Оформление заказа';
				break;
		}
		return $title_header = array('id' => $id, 'title' => $title, 'header' => $headerCont);
	}

//функция определяющая ссылку на содержимое content по id
	function contentSet($id)
	{
		switch($id)
		{
			case 'contacts': $include = 'inc/content/contacts.php'; break;
			case 'catalog': $include = 'inc/content/catalog.php'; break;
			case 'reviews': $include = 'inc/content/reviews.php'; break;
			case 'admin': $include = 'inc/content/admin.php'; break;
			case 'additem': $include = 'inc/content/additem.php'; break;
			case 'adduser': $include = 'inc/content/adduser.php'; break;
			case 'orders': $include = 'inc/content/orders.php'; break;
			case 'basket': $include = 'inc/content/basket.php'; break;
			case 'orderform': $include = 'inc/content/orderForm.php'; break;
			default: $include = 'inc/content/home.php';
		}
		return $include;
	}
///////////////////////////////////SEND/////////////////////////////////////////////////

//функция проверяющая метод передачи данных
	function sendMethod()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
			return true;
		else
			return false;
	}

//функция проверяющая метод передачи данных и не имеют ли полученные переменные значение NULL
	function sendIsFine()
	{
	//массив полученных аргументов
		$args = func_get_args();
		if(sendMethod())
		{
		//проверка аргументов на отличие от NULL
			foreach($args as $field)
			{
				if($_POST["$field"] == NULL)
				{
					echo $error_find = "Не все поля были заполненны!";
					exit;
				}
			}
		}
		else
			return false;
		return true;
	}


///////////////////////////////////REVIEWS///////////////////////////////////////////////

//функция сохранения отзыва в бд + проверка метода передачи + отличность от NULL
	function saveReview()
	{
		if(sendIsFine(name, email, review))
		{
		//присваивание переменным значения, полученным из формы отзыва
			$name = cleanStr($_POST['name']);
			$email = cleanStr($_POST['email']);;
			$review = strip_tags($_POST['review']);;
			$rate = cleanInt($_POST['rate']);

			global $link;

		//sql запрос на добавление отзыва в бд
			$sql = "INSERT INTO reviews(name, email, review, rate)
						VALUES(?,?,?,?)";
		//подготавливаем mysql server к запросу
			$stmt = mysqli_prepare($link, $sql);
		//привязываем переменные к подготавливаемому запросу
			mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $review, $rate);
		//выполняем подготовленный запрос
			mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
		//переадресация на чистую форму отзыва
			header('Location:' . $_SERVER['REQUIRED_URI']);
			exit;
		}
	}

//функция вывода n отзывов, принимающая путь к представлению отзыва в html и количество отзывов
	function showReview($pathReviewView, $n)
	{
		global $link;
	//sql запрос на выборку 5 последних отзывов
		$sql = "SELECT id, name, email, review, rate, UNIX_TIMESTAMP(date) as date
				FROM reviews
				ORDER BY id DESC LIMIT $n";
	//переменная содержащая результат запроса(массив)
		$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	//вывод всех отзывов
		while($row = mysqli_fetch_assoc($result))
		{
			$id = $row['id'];
			$name = $row['name'];
			$email = $row['email'];
			$review = $row['review'];
			$rate = $row['rate'];
			$date = date('d-m-Y H:i:s', $row['date']);

			require "$pathReviewView";
		}
	}
//функция удаления отзыва
	function deleteReview()
	{
		if(isset($_GET['del']))
		{
			sessionAdmin();
			global $link;
		//получение id удаляемого отзыва
			$id = cleanInt($_GET['del']);
		//sql запрос на удаление
			$sql = "DELETE FROM reviews
				WHERE reviews.id = '$id'";
		//подготавливаем mysql server к запросу
			$stmt = mysqli_prepare($link, $sql);
		//привязываем переменные к подготавливаемому запросу
			mysqli_stmt_bind_param($stmt, 'i', $id);
		//выполняем подготовленный запрос
			mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
		//переадресация на чистую форму отзыва
			header('Location:' . $_SERVER['HTTP_REFERER']);
			exit;
		}
	}


///////////////////////////////////ADMIN//////////////////////////////////////////

//функция добавления товара в каталог
	function addItem()
	{
		if(sendIsFine(brand,model,charac,desc,photos,price,instock))
		{
			global $link;

			$brand = cleanStr($_POST['brand']);
			$model = cleanStr($_POST['model']);
			$charac = strip_tags($_POST['charac']);
			$desc = strip_tags($_POST['desc']);
			//$photos = strip_tags($_POST['photos']); - для отдельного запроса в таблицу photos
			$price = cleanInt($_POST['price']);
			$instock = cleanInt($_POST['instock']);

		//sql запрос на добавление отзыва в бд
			$sql = "INSERT INTO catalog (brand,model,charac,description,price,instock)
						VALUES(?,?,?,?,?,?)";
		//подготавливаем mysql server к запросу
			$stmt = mysqli_prepare($link, $sql);
		//привязываем переменные к подготавливаемому запросу
			mysqli_stmt_bind_param($stmt, 'ssssii', $brand, $model, $charac, $desc, $price, $instock);
		//выполняем подготовленный запрос
			mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
		//переадресация на чистую форму отзыва
			header('Location:' . $_SERVER['REQUIRED_URI']);
			exit;
		}
	}
//функция добавления нового пользователя/администратора в бд, возвращает результат
	function addUser()
	{
		if(sendIsFine(login))
		{
		//проверяем не сущетсвует ли пользователь с таким именем
			if(!userExists(cleanStr($_POST['login'])))
			{
				$login = cleanStr($_POST['login']);
				//проверяем метод передачи данных и не имеют ли полученные переменные значение NULL
				if (sendIsFine(password, salt, iteration, rights))
				{
					global $link;

					$password = cleanStr($_POST['password']);
					$salt = cleanStr($_POST['salt']);
					$iteration = cleanInt($_POST['iteration']);
					$rights = cleanStr($_POST['rights']);

					//генерируем хеш
					$hash = genHash($password, $salt, $iteration);

					//sql запрос на добавление отзыва в бд
					$sql = "INSERT INTO users (login,hash,salt,i,rights)
						VALUES(?,?,?,?,?)";
					//подготавливаем mysql server к запросу
					$stmt = mysqli_prepare($link, $sql);
					//привязываем переменные к подготавливаемому запросу
					mysqli_stmt_bind_param($stmt, 'sssis', $login, $hash, $salt, $iteration, $rights);
					//выполняем подготовленный запрос
					mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));

					if ($login == 'admin')
						return $result = "Алминистратор $login добавлен";
					else
						return $result = "Пользователь $login добавлен";
				}
			}
			else
				return $result = "Пользователь с таким именем уже существует";
		}
	}

///////////////////////////////////SECURITY//////////////////////////////////////////
//функция генерирующая salt, возвращает salt
	function genSalt()
	{
			return $salt = str_replace('=', '', base64_encode(md5(microtime() . '4324IHDDDS8127DAS992NSQ')));
	}
//функция генерирующая хеш пароля, принимает агрументы: пароль, соль и кол-во итераций
	function genHash($password, $salt, $iteration)
	{
		for($i = 0; $i < $iteration; $i++)
		{
			$hash = sha1($password . $salt);
		}
		return $hash;
	}

//функция проверяющая наличие пользователя с заданным именем, возвращает true, если существует
	function userExists($login)
	{
		global $link;
	//запрос с текущим login пользователя
		$sql = "SELECT id FROM users WHERE login = '$login'";
	//выполнение запроса и возврат false в случае, если пользователь не найден
		if(!mysqli_num_rows(mysqli_query($link, $sql)))
			return false;
		return true;
	}

///////////////////////////////////CATALOG//////////////////////////////////////////

//функция формирования страниц, принимает путь к представлению товара в каталоге
/**
 * @param $pathCatalogView
 * @return array
 */
function pageMaker($pathCatalogView)
	{
		global $link;
	//количество товара на странице
		$items_per_page = 3;
	//номер страниы по умолчанию
		$cur_page = 1;
	//получаем номер страницы, если он существует и больше 0
		if (isset($_GET['page']) && cleanInt($_GET['page']) > 0)
		{
			$cur_page = cleanInt($_GET['page']);
		}

	//способ сортировки по умолчанию отсутствует
		$sort = "";

	//получаем способ сортировки
		if (isset($_GET['sort']))
		{
			if (cleanStr($_GET['sort']) == 'baz')
			{
				$sort = 'baz';
				$sql_sort = ' ORDER BY brand ';
			}
			if (cleanStr($_GET['sort']) == 'paz')
			{
				$sort = 'paz';
				$sql_sort = ' ORDER BY price DESC ';
			}
			if (cleanStr($_GET['sort']) == 'pza')
			{
				$sort = 'pza';
				$sql_sort = ' ORDER BY price ';
			}
		}

	//sql запрос на выборку всех id,нужен для определения количества товаров
		$sql_n = 'SELECT id FROM catalog';
	//получаем количество строк результата запроса
		$total_items = mysqli_num_rows(mysqli_query($link, $sql_n));
	//количество страниц с округлением в большую сторону
		$total_pages = ceil($total_items / $items_per_page);
	//проверяем номер страницы, если он больше возможной последней страницы, присваеваем ему последнюю
		if ($cur_page > $total_pages)
			$cur_page = $total_pages;
	//позиция с которой будут выводиться товары из бд
		$first_id = $cur_page * $items_per_page - $items_per_page;
	//проверка количества товаров на текущей странице
		if (($total_items - ($cur_page * $items_per_page)) < 0)
			$items_per_page = $total_items - (($cur_page - 1) * $items_per_page);
	//sql запрос на выборку всех записей с сортировко/без + LIMIT
		$sql = "SELECT id, brand, model, charac, description, price, instock
						FROM catalog
						$sql_sort LIMIT $first_id,$items_per_page";
	//результат sql запроса
		$result = mysqli_query($link, $sql) or die(mysqli_error($link));
	//сохранение результата запроса в массив
	//	$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$items = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$items[] = $row;
		}
	//вывод товара по шаблону из $path
		for($temp = 0; $temp < $items_per_page; $temp++)
		{
			$item = $items[$temp];

			$id = $item['id'];
			$brand = $item['brand'];
			$model = $item['model'];
			$charac = $item['charac'];
			$description = $item['description'];
			$price = $item['price'];
			$instock = $item['instock'];

		//проверка количества товара на складе, если 0, то кнопка в корзину не активна
			if($instock > 0)
				$to_basket = '<a href = "'. $_SERVER['REQUEST_URI'] . '&idba=' . $id .'">В корзину</a>';//ссылка с $id товара

			require "$pathCatalogView";
		}
	//инициализация ссылок на номера последних и первых двух страниц(если существуют)
	//с проверкой на наличие сортировки
		if(!empty($sort))
			$sort = '&sort=' . $sort;

		if(($cur_page - 2) > 0)
			$page2first = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page - 2) . $sort . '">' . ($cur_page - 2) . '</a>';
		if(($cur_page - 1) > 0)
			$page1first = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page - 1) . $sort . '">' . ($cur_page - 1) . '</a>';
		if(($cur_page + 2) <= $total_pages)
			$page2end = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page + 2) . $sort . '">' . ($cur_page + 2) . '</a>';
		if(($cur_page + 1) <= $total_pages)
			$page1end = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page + 1) . $sort . '">' . ($cur_page + 1) . '</a>';

	//инициилизация стрелок для перемотки и проверка нужны ли они
		if($cur_page != 1)
			$page_first = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page=1'
				. $sort . '"><<</a><a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page - 1) . $sort . '"><</a>';
		if($cur_page != $total_pages)
			$page_last = '<a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. ($cur_page + 1) . $sort . '">></a><a href = "' . $_SERVER['SCRIPT_NAME'] . '?id=catalog&page='
				. $total_pages . $sort . '">>></a>';

	//вывод ссылок на номера страниц
		return $arrows = array('first' => $page_first,
								'2first' => $page2first,
								'1first' =>$page1first,
								'cur' => $cur_page,
								'1end' => $page1end,
								'2end' => $page2end,
								'last' => $page_last);
	}


///////////////////////////////////BASKET//////////////////////////////////////////

//функция сохранения корзины с товарами в куки
	function saveBasket()
	{
		global $basket;

	//сначала массив сериализуется в строку, затем сериализуется
	//по стандарту base64(строка будет состоять из чисел и букв) на случай, если в строках будут апострафы
		$basket = base64_encode(serialize($basket));
	//куки сохраняется на максимальное значение времени
		setcookie('basket', $basket, 0x7FFFFFFF);
	}

//сохраняет корзину в куки, если её там ещё нет, если есть, то десиреализирует содержимое куки
//в переменну хранящую массив содержимого корзины
	function basketInit()
	{
		global $basket, $count;

		if(!isset($_COOKIE['basket']))
		{
		//генерируем уникальный id для заказа
			$basket = array('orderid' => uniqid());
			saveBasket();
		}
		else
		{
			$basket = unserialize(base64_decode($_COOKIE['basket']));
		//подсчёт количества товаров в корзине. (-1) - для вычета orderid
	//	echo '<pre>';
	//	print_r($basket);exit;
			$count = count($basket) - 1;
		}
	}

//функция добавления товара в корзину. Принимает id и количество товара
	function addToBasket()
	{
	//проверка существует ли $id
		if(isset($_GET['idba']))
		{
		//инициализируем $id товара
			$id = cleanInt($_GET['idba']);

			global $basket;
			//добавление товара в массив

		//echo '<pre>';
		//print_r($basket);exit;

			$basket[$id] = 1;
			//сохранение корзины в куки
			saveBasket();
			header('Location: '. $_SERVER['HTTP_REFERER']);
			exit;
		}
	}

//функция возвращающая содержимое корзины в виде ассоциативного массива
	function basketToArray()
	{
		global $link, $basket, $order_id;
	//запись номера заказа в переменную
		$order_id = $basket['orderid'];
	//массив id товаров
		$keys = array_keys($basket);
	//удаление первой строки с содержимым orderid(id заказа)
		array_shift($keys);
	//Если колличество оставшився элементов в массиве после извлечения orderid отлично от нуля то:
		if(count($keys))
		{
		//массив склеивается в строку через ',' для sql выборки по id
			$ids = implode(',', $keys);
		}
		else
		{
			$ids = 0;
		}
	//запрос на выборку всех товаров по id
		$sql = "SELECT id, brand, model, charac, description, price, instock
				FROM catalog
				WHERE id IN($ids)";
		if(!$result = mysqli_query($link, $sql))
			return false;
	//инициализируем массив содержимого корзины
		$items = array();

		while($row = mysqli_fetch_assoc($result))
		{
			$row['quantity'] = $basket[$row['id']];
			$items[] = $row;
		}
		mysqli_free_result($result);

		return $items;
	}
//функция вывода корзины по заданному шаблону, принимает аргумент - путь к шаблону
//+переадресовывает в каталог, если корзина пуста
	function showBasket($pathBasketView)
	{
		$items = basketToArray();

		global $order_id, $n, $total_price;
	//порядковый номер товара в корзине
		$n = 1;
	//сумма
		$total_price = 0;
	//если корзина пуста -> в каталог
		if(!count($items))
		{
			header('Location:' . $_SERVER['SCRIPT_NAME'] . '?id=catalog');
			exit;
		}

		foreach($items as $item)
		{
			$id = $item['id'];
			$brand = $item['brand'];
			$model = $item['model'];
			$price= $item['price'];
			$quantity = $item['quantity'];
			$delete_from_basket = '<a href ="' . $_SERVER['REQUEST_URI'] . '&idbd=' . $id . '">Удалить</a>';

			require "$pathBasketView";
			$total_price += $price;
			$n++;
		}

	}
//функция удаления из корзины. Принимает аргумент: id товара
	function deleteItemFromBasket()
	{
	//проверка существует ли $id
		if(isset($_GET['idbd']))
		{
			//инициализируем $id товара
			$id = cleanInt($_GET['idbd']);

			global $basket;
			//удаление товара из массива коризны
			unset($basket[$id]);
			//сохранение корзины в куки
			saveBasket();
			header('Location: '. $_SERVER['HTTP_REFERER']);
			exit;
		}

	}

///////////////////////////////////ORDER///////////////////////////////////////////

//функция добавления заказа в бд
	function addOrder()
	{
		if(sendIsFine(name,email,telephone,address))
		{

			$name = cleanStr($_POST['name']);
			$email = cleanStr($_POST['email']);
			$telephone = cleanStr($_POST['telephone']);
			$address = cleanStr($_POST['address']);
		//содержимое корзины
			$items = basketToArray();

			global $link, $order_id;



			foreach($items as $item)
			{
				$id = $item['id'];
				$brand = $item['brand'];
				$model = $item['model'];
				$price = $item['price'];
				$quantity = $item['quantity'];

			//sql запрос на добавление заказа в orders
				$sql = "INSERT INTO orders (oid,iid,brand,model,price,quantity)
						VALUES(?,?,?,?,?,?)";
			//подготавливаем mysql server к запросу
				$stmt = mysqli_prepare($link, $sql);
			//привязываем переменные к подготавливаемому запросу
				mysqli_stmt_bind_param($stmt, 'sissii', $order_id, $id, $brand, $model, $price, $quantity);
			//выполняем подготовленный запрос
				mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
			}
		//sql запрос на добавление заказа в ordersinfo
			$sql = "INSERT INTO ordersinfo (oid,name,email,telephone,address)
						VALUES(?,?,?,?,?)";
		//подготавливаем mysql server к запросу
			$stmt = mysqli_prepare($link, $sql);
		//привязываем переменные к подготавливаемому запросу
			mysqli_stmt_bind_param($stmt, 'sssis', $order_id, $name, $email, $telephone, $address);
		//выполняем подготовленный запрос
			mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));

		//удаление куки + перевод времени назад, на всякий случай
			setcookie('basket', '', time()-3600);
			//переадресация на чистую форму отзыва
			header('Location:' . $_SERVER['SCRIPT_NAME'] . '?id=catalog');
			exit;
		}
	}

//функция отображения заказов для администратора? принимает шаблон отображения заказа
//принимает путь представления информации о заказе и путь к представлению товаров
	function showOrders($pathOrdersInfoView,$pathOrdersView)
	{
		global $link;
		//sql запрос на выборку заказов
		$sql = "SELECT oid, name, email,telephone, address, date
				FROM ordersinfo";
		//переменная содержащая результат запроса(массив)
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		//вывод всех отзывов
	//	$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$result = array();
		while($row = mysqli_fetch_assoc($res))
		{
			$result[] = $row;
		}
		foreach($result as $row)
		{
		//echo '<pre>';
		//print_r($row);exit;
			$order_id = $row['oid'];
			$name = $row['name'];
			$email = $row['email'];
			$telephone = $row['telephone'];
			$address = $row['address'];
			$date = $row['date'];

			require "$pathOrdersInfoView";

			$sql = "SELECT oid, brand, model, price, quantity
				FROM orders
				WHERE oid ='$order_id'";
			//переменная содержащая результат запроса(массив)
			$result = mysqli_query($link, $sql) or die(mysqli_error($link));
			//порядковый номер товара в заказе
			$n = 1;
			//сумма
			$sum = 0;
			while($row = mysqli_fetch_assoc($result))
			{
				$brand = $row['brand'];
				$model = $row['model'];
				$price = $row['price'];
				$quantity = $row['quantity'];

				require "$pathOrdersView";
				$n++;
				$sum += $price;
			}
			echo '</table>';
			echo "Всего к оплате: $sum";
		}
	}
///////////////////////////////////SESSION/////////////////////////////////////////

//функция запускающая сессию админа
	function sessionAdmin()
	{
		session_start();
		if(!isset($_SESSION['admin']))
		{
			header('Location: /inc/content/login.php?ref=' . $_SERVER['REQUEST_URI']);
			exit;
		}
		return true;
	}

//функция завершения сеанса пользователя
	function logOut()
	{
		if(isset($_GET['logout']))
		{
			session_destroy();
			header('Location: ' . $_SERVER['SCRIPT_NAME']);
			exit;
		}
	}
?>