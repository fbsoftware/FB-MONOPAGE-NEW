<style>
ul {
    display: flex;
    list-style: none;
    gap: 20px;
}   
ul li a {
    color: var(--color);    
    text-decoration: underline;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: normal;
    font-family: 'Arial', sans-serif;
    font-size: .5em;
}
    </style>
    <?php
echo "<ul>";
echo "<li><a href='/FB-MONOPAGE/nav-builder/nav-builder.php'>Menù</a></li>";
echo "<li><a href='/FB-MONOPAGE/admin/gest_pages.php'>Pagine</a></li>";
echo "<li><a href='/FB-MONOPAGE/index.php'>Sito</a></li>";
echo "</ul>";

