# обработчик ленты YML

Старт разработки

Принцип работы похож на пакет zend-feed

Чтение ленты YML

```php

/*импорт из файла*/
$feed=Reader::importFile('data/1.xml');

/*импорт из URL*/
$feed=Reader::import('http://*******');

/*импорт из строки*/
$feed=Reader::importString('*****');

/*чтение информации в секции описания, например, name*/
$name=$feed->getName();

/*обход всех элементов в секции offers*/
foreach ($feed as $entry) {
     $description=$entry->getDescription();
     
     /*возвращает в виде коллекции (спец объект), которую можно преобразовать в массив*/
     $pics=$entry->getPicturies();
}


/*пример создания YML*/
        $feed=new Feed();
        $feed->setDateModified();
        $feed->setName("Имя компании");
        $feed->setCompany("Имя компании INC");
        $feed->setUrl("http://wwww.345456.ru");
        $feed->addCurrencies([
            ["id"=>"RUR","rate"=>1],
            ["id"=>"USD","rate"=>10],
        ]);
    
        $feed->setCategories([
            ["id"=>11,"parentId"=>1,"label"=>"категория 1"],
            ["id"=>1,"label"=>"категория 2"],
        ]);

        
        //сам товар
        $entry = $feed->createEntry(["id"=>12,"bid"=>111,"cbid"=>1,"available"=>true]);
        $entry->setUrl("http://23423534.ru/sdfdfgdfgdfg");
        $entry->setPrice(234234.22);
        $entry->setCurrencyId("RUR");
        
        $entry->setCategoriesId([45,1,2]);
        $entry->addCategoryId(405);
        
        $entry->addpicture("http://876.ry/444");
        $entry->addpicture("http://876.ry/111");
        
        $entry->setStore(true);
        $entry->setDelivery(true);
        $entry->setManufacturer_warranty(true);
        $entry->setName("Имя товара");
        $entry->setVendor("производитель товара");
        $entry->setModel("модель товара");
        $entry->setDescription("Описание товара");
        $entry->setSales_notes("sales_notes элемент");
        $entry->setbarcode("barcode элемент");
        $entry->setAge(0);
        
        $entry->setParams([
            ["name"=>11,"unit"=>1,"label"=>"категория 1"],
            ["name"=>110,"unit"=>01,"label"=>"категория 10"],
        ]);

        
        
        $feed->addEntry($entry);
        
        /*следующий элемент*/
        
        echo $feed->export();



```
