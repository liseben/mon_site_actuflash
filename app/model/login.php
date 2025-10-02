<?php
/**
 * valider() vérifie que le nom entré par l'utilisateur existe bien et que le mot de passe correspond dans
 *  le fichier login.csv
 * @param string $user nom entré par l'utilisateur
 * @param string $mdp mot de passe entré par l'utilisateur
 * @return array	[0] : true si existe, false si n'existe pas
 * 								[1, 2] : details sur l'utilisateur
 * @return bool succès ou échec de l'opération d'identification
 */
function login_validate(string $user, string $mdp): array
{
	try
	{
		// lecture fichier
		$fh = fopen( '../asset/database/login.csv', 'r' );
		while( ! feof($fh) )// renvoie true si le pointeur n'est pas encore à la fin du fichier et false si c'est le cas
		{
			$ligne = fgets($fh);
			$user_info = explode( ';', trim($ligne) );
			
			if( $user_info[0] == $user && $user_info[1] == $mdp )
			{
				// l'utilisateur a été identifié
				fclose($fh);
				return array( true, $user_info[0], $user_info[2] ); // qui retourne les infos sur l'utilisateur
			}
		}
		// l'utilisateur n'a pas été identifié
		fclose($fh);
		return array( false, null, null );
	}
	catch( Exception $e) 
	{
		echo "Problem while reading file login.csv : " . $e->getMessage();
		return array( false, null, null );
	}
}

function save_login_to_file($username, $role) {
    // chemin vers le fichier des utilisateurs connectés
    $file_path = '../asset/database/utilisateurs_loggers.txt';

    // date et heure actuelles
    $date = date('Y-m-d H:i:s');



    // information de connexion
    $login_info = "$username;$role;$date";

    // écrire dans le fichier (ajouter à la fin)
    return file_put_contents($file_path, $login_info, FILE_APPEND) !== false;
}


function remove_login_from_file($username) {
    // chemin vers le fichier des utilisateurs connectés
    $file_path = '../asset/database/utilisateurs_loggers.txt';

    // si le fichier n'existe pas, rien à faire
    if (!file_exists($file_path)) {
        return true;
    }

    // lire le contenu du fichier
    $lines = file($file_path);
    $new_content = '';

    // filtrer les lignes pour enlever celle de l'utilisateur
    foreach ($lines as $line) {
        $user_data = explode(';', $line);
        // si ce n'est pas l'utilisateur qui se déconnecte, garder la ligne
        if ($user_data[0] !== $username) {
            $new_content .= $line;
        }
    }

    // écrire le nouveau contenu dans le fichier
    return file_put_contents($file_path, $new_content) !== false;
}

?>