<?php

class File {

	private $name;
	private $handler;

	private $content = false;

	public function __construct($filename, $mode = 'a+') // В construct прописываем какой файл открываем и функцию Open- значения для переменных функци
	{
		$this->name = $filename;
		$this->open($mode);
	}

	public function __destruct() // Прописываем функцию close
	{
		$this->close();
	}

	public function open($mode) //Функця открытия файла
	{
		$this->handler = fopen($this->name, $mode); //Обработчик - открываем файл функцией fopen с режимом, указанным в конструкторе

		if($this->handler === FALSE)
			throw new Exception('Unable to open file'); //В случае, если не удалось открыть файл выводм ошибку
	}

	public function close()
	{
		fclose($this->handler); //Закрываем обработчик
	}

	public function read()
	{
		if(!empty($this->content)) //Проверяем - пустой файл или нет. Если нет - выводим содержимое
			return $this->content;

		if(get_resource_type($this->handler)== 'Unknown') //Определяем - открыт файл или нет. Если не открыт - выводим сообщение
			throw new Exception('Call Open method first');

		$this->content = '';
		$length = 1000; //Огрничиваем количество считываемых символов
		while ($part = fread($this->handler, $length))
		{
			$this->content .= $part; //Объединяем выводимые части в одну строку
		}
		return $this->content; //Возвращаем строку целиком
	}

	public function split()
	{
		if(empty($this->content)) //Проверям - пустой файл или нет. Если пустой - выводим сообщение о том, что нечего разделять
			throw new Exception('No data to split');

		return explode("\n", $this->content); // В противном случае разделяем строки, в качестве разелителя используем знак переноса строки (энтер)
	}

	public function write($content)
	{
		$content_length = mb_strlen($content); // определяем длину контента
		$length = 1000; //указываем количество символов, которое выводить за 1 раз
		$count = ceil($content_length / $length); // раздлив длиу контента на количество символов, вводимых за один раз подсчитываем количество иттераций
		for($offset = 0; $offset < $content_length; $offset += $length) //запускаем цикл для записи. При каждой иттерации считается количество уже использованных символов, цкл прекратится, когда используют все символы строки
		{
			$part = mb_substr($content, $offset, $length); //Получаем длинну части строки
			fwrite($this->handler, $part); //Записывем очередную часть строк в файл
			// echo $offset . '<br';
		}
	}

	public function append($new_content)
	{
		if(empty($this->content)) //Проверям - есть ли текст для добавления. Если нет - выводим ошибку.
			throw new Exception('No text for append');

		fwrite($this->handler, $new_content); //Записывем очередную часть строк в файл

	}

	public function prepend($new_content)
	{
		if(empty($this->content)) //Проверям - есть ли текст для добавления. Если нет - выводим ошибку.
			throw new Exception('No text for append');

		file_put_contents($this->name, $new_content . " ". file_get_contents($this->name)); //считываем при помощи file_get_contents все содержимое файла, конкатенируем перед ним текст, и помещаем обратно в файл через file_put_contents

	}

}