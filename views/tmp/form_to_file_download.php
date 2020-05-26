
<form id="form" method="POST" action="/" enctype="multipart/form-data">

    <!-- Когда будет вызываться модальное окно с формой для загрузки там будет указан:
        1) один фойл / несколько


        По технической части:
        1) значение для input-hidden - в какое место грузить файл

        p.s. это все формирует сервак, ручками вообще ничего не пишется
     -->

    <input id="input-file" type="file" multiple name="download_files[]">

    <br/>


    <input type="hidden" name="id_application" value="1739">

    <input type="hidden" name="mapping_level_1" value="2">
    <input type="hidden" name="mapping_level_2" value="1">

    <input type="hidden" name="id_structure_node" value="1">

    <input id="submit_button" type="submit" value="Загрузить файл">

    <br/>

    <!-- тут прикрутишь красивую полоску -->
    <span id="progress_bar"></span>
</form>

<br/><br/><br/>

Проверка файла
<form id="form_check" method="POST" action="/" enctype="multipart/form-data">

    <input type="hidden" name="id_application" value="1739">
    <input type="text" name="id_file" value="31">

    <input type="hidden" name="mapping_level_1" value="1">
    <input type="hidden" name="mapping_level_2" value="1">


    <input id="submit_button" type="submit" value="проверить файл">

</form>


<p><a href="../home/file_unloader?id_application=1739&fs_name=2dfdc134ae643b7bc6665c45e5e7e28170ac8675e731bb7af651377bdf248cb1cdbb21a8be58968f&file_name=Криптоконтейнер_74-1-1-2-013207-2020.xml">Криптоконтейнер_74-1-1-2-013207-2020.xml</a></p>





