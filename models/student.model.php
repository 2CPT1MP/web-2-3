<?php
require_once('bio.model/bio.model.php');
require_once('interests.model/interests.model.php');
require_once('studies.model/studies.model.php');
require_once('studies.model/schedule-record.model.php');
require_once('test.model/test.model.php');
require_once('test.model/test-question.model.php');
require_once('test.model/answer.model.php');

class Student {
    private string $name, $group, $labTitle;
    private int $labNum;

    private Bio $bio;
    private Interests $interests;
    private Studies $studies;
    private Test $test;
    private array $photos  = [];

    public function getName(): string { return $this->name; }
    public function getBio(): Bio { return $this->bio; }
    public function getInterests(): Interests { return $this->interests; }
    public function getStudies(): Studies { return $this->studies; }
    public function getGroup(): string { return $this->group; }
    public function getLabTitle(): string { return $this->labTitle; }
    public function getLabNum(): int { return $this->labNum; }
    public function getPhotos(): array { return $this->photos; }
    public function getTest(): Test { return $this->test; }
    public function addPhoto(string $title, string $path): void { $this->photos[] = new Photo($title, $path); }


    public function __construct() {
        /**
        * !!! Sample data !!!
        */
        $this->name = "Виниченко А.А.";
        $this->group = "ИС/б-18-2-о";
        $this->labNum = 1;
        $this->labTitle = "Реализация собственной MVC-архитектуры";

        $this->bio = new Bio();
        $this->bio->addArticle('Общая информация', 'Я, Виниченко Андрей Андреевич, родился в 10.09.2000 в г. Ровеньки, Луганская область.');
        $this->bio->addArticle('Образование', 'В 2008 г. пошел в первый класс среднеобразовательной школы № 41. 
                                    Во время обучения в школе параллельно окончил курсы слесаря - моториста. В 2018 г. получил аттестат о среднем образовании. 
                                    На следующий год устроился слесарем по ремонту автомашин в производственном управлении транспорта Главташкентстроя.
							        С 2016 г. по 2018 г. проходил спецкурсы водителей на автобус с последующей стажировкой. 
							        После окончания курсов по 2018 г. работал водителем.');
        $this->bio->addArticle('Работа', 'С 2016 г. по 2018 г. работал разъездным фотографом.
							        С 2019 г. по 2020 г. в кооперативе ТЭД проработал мастером цеха.');
        $this->bio->addArticle('Текущее состояние', 'Следующим этапом в моей жизни был в 2020 г. переезд в город Севастополь, 
                                    где и проживаю вместе со своей семьей в настоящее время. Здесь и началась моя общественная деятельность. 
                                    На протяжении долгих лет принимал активное участие в обустройстве своего поселка. На данный момент добиваюсь 
                                    решения одной из важных проблем поселка Школьное – развязки удобных подъездных путей в населенный пункт. 
                                    Также проявляю большую инициативу в корректировке проекта автомобильной дороги Харьков – Симферополь – Севастополь 
                                    на территории п. Школьное, благоприятного для жителей населенного пункта.
							        Неженат, детей нет. Беспартийный; судимости не имеется. Проживаю по адресу: г. Севастополь, Нахимовский район, 
							        ул. Одинцова, д. 5, кв. 12.');

        $this->interests = new Interests();
        $this->interests->createCategory('Любимые занятия');
        $this->interests->addItemToCategory('Любимые занятия', 'интерес к компьютерным, программным и техническим новшествам');
        $this->interests->addItemToCategory('Любимые занятия', 'изучение профессиональной литературы');
        $this->interests->addItemToCategory('Любимые занятия', 'чтение исторических романов, философских книг, классической литературы, в том числе и современной');
        $this->interests->addItemToCategory('Любимые занятия', 'игра на музыкальных инструментах, увлечение классической музыкой');
        $this->interests->addItemToCategory('Любимые занятия', 'моделирование одежды, создание дизайнерских кукол;');

        $this->interests->createCategory("Любимые книги");
        $this->interests->addItemToCategory('Любимые книги', '"Анна Каренина" - Лев Толстой');
        $this->interests->addItemToCategory('Любимые книги', '«Автостопом по галактике» — Дуглас Адамс');
        $this->interests->addItemToCategory('Любимые книги', '«Алиса в Стране чудес» — Льюис Кэрролл');
        $this->interests->addItemToCategory('Любимые книги', '«Алхимик» — Пауло Коэльо');
        $this->interests->addItemToCategory('Любимые книги', '«Американский психопат» — Брет Истон Эллис');

        $this->interests->createCategory("Любимая музыка");
        $this->interests->addItemToCategory('Любимая музыка', 'Imagine Dragon - Natural');
        $this->interests->addItemToCategory('Любимая музыка', 'Imagine Dragons - Dream');
        $this->interests->addItemToCategory('Любимая музыка', 'Imagine Dragons - Whatever it takes');
        $this->interests->addItemToCategory('Любимая музыка', 'Malfa - So Long');

        ScheduleRecord::setSemestersCount(4);
        ScheduleRecord::setFieldCount(3);
        $this->studies = new Studies();
        $this->studies->setUniversity("Севастопольский государственнный университет");
        $this->studies->setDepartment("Информационные системы");
        $this->studies->setStudyProgram("09.03.02 Информационные системы и технологии");

        $ecology = new ScheduleRecord("Экология");
        $ecology->addFieldToSemester(3, 1, 1);
        $ecology->addFieldToSemester(3, 2, 0);
        $ecology->addFieldToSemester(3, 3, 1);

        $math = new ScheduleRecord("Высшая математика");
        $math->addFieldToSemester(1, 1, 3);
        $math->addFieldToSemester(1, 2, 0);
        $math->addFieldToSemester(1, 3, 3);

        $math->addFieldToSemester(2, 1, 3);
        $math->addFieldToSemester(2, 2, 0);
        $math->addFieldToSemester(2, 3, 3);

        $math->addFieldToSemester(3, 1, 2);
        $math->addFieldToSemester(3, 2, 0);
        $math->addFieldToSemester(3, 3, 2);

        $russian = new ScheduleRecord("Русский язык");
        $russian->addFieldToSemester(1, 1, 1);
        $russian->addFieldToSemester(1, 2, 0);
        $russian->addFieldToSemester(1, 3, 2);

        $discreteMath = new ScheduleRecord("Дискретная математика");
        $discreteMath->addFieldToSemester(1, 1, 2);
        $discreteMath->addFieldToSemester(1, 2, 0);
        $discreteMath->addFieldToSemester(1, 3, 1);

        $discreteMath->addFieldToSemester(2, 1, 3);
        $discreteMath->addFieldToSemester(2, 2, 0);
        $discreteMath->addFieldToSemester(2, 3, 2);

        $this->studies->addScheduleRecord($ecology);
        $this->studies->addScheduleRecord($math);
        $this->studies->addScheduleRecord($russian);
        $this->studies->addScheduleRecord($discreteMath);

        for ($index = 1; $index <= 15; $index++)
            $this->addPhoto("Изображение $index", "/photos?id=$index");
    }
}