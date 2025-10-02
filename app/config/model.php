<?php

const MACHINE = "home"; // "classe38" ou  "home" ou ... ce qu'on veut

/**
 * DATABASE_Type : "SQL" ou "JSON"
 */
const DATABASE_TYPE = "JSON";


switch (DATABASE_TYPE){
    case  "SQL":
        define("DATABASE_NAME", "press_2024_v03");
        break;
    case  "JSON":
        define("DATABASE_NAME", "../asset/database/article.json");
        break;
}


switch(MACHINE) { // config pour gérer les machines soit à la maison , soit à l'école
	// ISFCE, classe 38
	case "classe38":
		define( "DATABASE_PORT", 3307 ); 	// MAriaDB
		define( "DATABASE_USERNAME", "root" );
		define( "DATABASE_PASSWORD", "" );
		break;
	case "home":
		define( "DATABASE_PORT", 3306 );  	// MysSQL
		define( "DATABASE_USERNAME", "root" );
		define( "DATABASE_PASSWORD", "root" );
		break;
}

const DATABASE_DSN =  "mysql:host=localhost;dbname=".DATABASE_NAME.";port=".DATABASE_PORT.";";




//config pour gérer les JSON ou les Base de données