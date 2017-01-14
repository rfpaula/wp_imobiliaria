<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */
 
//define('WP_HOME','http://www.imobiliariaipe.com');
//define('WP_SITEURL','http://www.imobiliariaipe.com');
define( 'WP_AUTO_UPDATE_CORE', false );


// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'wp_imobiliaria');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'wp_imobiliaria');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'rfp183654');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '%|/u-R2yJD/!EkVhr$.0<FNVC-D(+ojiB#46:gBx.pDsp 5.Y48!y*yVNS(LCuvt');
define('SECURE_AUTH_KEY',  'AnXS+GPBUmoF%IqnWoe-2+h0@Cnfbx/){aN]Z==fp6AESv,=xC;93a5|bb.w*^^`');
define('LOGGED_IN_KEY',    'NS{Q[Ww<m%d=R%2|A W4?n=i&=1ZATt~R86e}/1`AhdZ9612|T}=]evxMu?NvN^C');
define('NONCE_KEY',        'V$uI.|SJ35)IS;&jzHl9VS+m9d7D`Sfs`[C(~qA]P4`qrSVs{p;HmYK~P0W_Mw/*');
define('AUTH_SALT',        'tw`Uf^Y@r/J/!<yf~0MC:wh9*nGwzI6W9KC+3Rza6I+gLtvY311PcCTKEcKaVWG@');
define('SECURE_AUTH_SALT', 'y=ndc|ddf<V p: XS/#A;D%8:}q}0ZHI8AXYEc8JXtY90MeSR=vZQ{,Nm40ZL8r`');
define('LOGGED_IN_SALT',   '0T:p^blmP`#y{-2m<U i[=Xn/)B4=m.W!gTpmlinUGv;>;v<Q[QIRP-}-1&zZ3*a');
define('NONCE_SALT',       ':hk+Ce-u3sGp#[`,@0f@jQ/q`hvLZbj;;Gyxcee!XENx0Jn<,bNRT%gO<i2u@YG;');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
