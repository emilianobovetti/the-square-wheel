<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('CHARSET', 'DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

class Migration_Db extends CI_Migration {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function up()
	{
		$this->set_db_utf8();
		
		$this->admins_up();
		$this->articles_up();
		$this->tags_up();
		$this->links_tag_up();
		$this->comments_up();
		$this->ci_sessions_up();
	}

	public function down()
	{
		$this->links_tag_down();
		$this->comments_down();
		$this->tags_down();
		$this->articles_down();
		$this->admins_down();
		$this->ci_sessions_down();
	}

		/* UTF-8 */
	private function set_db_utf8()
	{
		$query = 'ALTER DATABASE `' . $this->db->database . '`
								CHARACTER SET utf8
								DEFAULT CHARACTER SET utf8
								COLLATE utf8_unicode_ci
								DEFAULT COLLATE utf8_unicode_ci;';
		$this->db->query($query);
	}

		/* Admin */
	private function admins_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `admins` (
							`ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
							`name` varchar(20) NOT NULL DEFAULT '',
							`password` varchar(60) NOT NULL DEFAULT '',
							`email` varchar(64) NOT NULL DEFAULT '',
							PRIMARY KEY (`ID`),
							UNIQUE KEY `admin_name` (`name`),
							UNIQUE KEY `email` (`email`)
						) ENGINE=InnoDB " . CHARSET . " AUTO_INCREMENT=1;";
		$this->db->query($query);

		/*
		$query = "INSERT INTO `admins` (`ID`, `name`, `password`, `email`)
							VALUES (1, 'Admin Name', 'HashedPasswordWithPHPASS', 'email');";
		
		$this->db->query($query);
		*/
	}

	private function admins_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `admins`;');
	}

		/* Article */
	private function articles_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `articles` (
							`ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
							`title` varchar(255) NOT NULL DEFAULT '',
							`body` text NOT NULL,
							`ring` int(1) NOT NULL DEFAULT 3,
							`author_id` int(9) unsigned NOT NULL DEFAULT 0,
							`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							PRIMARY KEY (`ID`),
							FOREIGN KEY (author_id) REFERENCES admins(ID)
								ON DELETE NO ACTION
								ON UPDATE CASCADE
						) ENGINE=InnoDB " . CHARSET . " AUTO_INCREMENT=1;";
		$this->db->query($query);
	}

	private function articles_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `articles`;');
	}

	private function tags_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `tags` (
							`ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
							`name` varchar(255) NOT NULL DEFAULT '',
							PRIMARY KEY (`ID`),
							UNIQUE KEY `tag_name` (`name`)
						) ENGINE=InnoDB " . CHARSET . " AUTO_INCREMENT=1;";
		$this->db->query($query);
	}

	private function tags_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `tags`;');
	}

	private function links_tag_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `links_article_tag` (
							`article_id` int(9) unsigned NOT NULL  DEFAULT 0,
							`tag_id` int(9) unsigned NOT NULL DEFAULT 0,
							UNIQUE KEY `tag` (`article_id`, `tag_id`),
							FOREIGN KEY (article_id) REFERENCES articles(ID)
								ON DELETE CASCADE
								ON UPDATE CASCADE,
							FOREIGN KEY (tag_id) REFERENCES tags(ID)
								ON DELETE CASCADE
								ON UPDATE CASCADE
						) ENGINE=InnoDB " . CHARSET . ";";
		$this->db->query($query);
	}

	private function links_tag_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `links_article_tag`;');
	}

		/* Comments database */
	private function comments_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `comments` (
							`ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
							`article_id` int(9) unsigned NOT NULL DEFAULT 0,
							`author` varchar(255) NOT NULL DEFAULT '',
							`body` text NOT NULL,
							`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							PRIMARY KEY (`ID`),
							FOREIGN KEY (article_id) REFERENCES articles(ID)
								ON DELETE CASCADE
								ON UPDATE CASCADE
						) ENGINE=InnoDB " . CHARSET . " AUTO_INCREMENT=1;";
		$this->db->query($query);
	}

	private function comments_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `comments`;');
	}

		/* Sessions database */
	private function ci_sessions_up()
	{
		$query = "CREATE TABLE IF NOT EXISTS `ci_sessions` (
							`session_id` varchar(40) NOT NULL DEFAULT '0',
							`ip_address` varchar(45) NOT NULL DEFAULT '0',
							`user_agent` varchar(120) NOT NULL,
							`last_activity` int(10) unsigned NOT NULL DEFAULT '0',
							`user_data` text NOT NULL,
							PRIMARY KEY (`session_id`),
							KEY `last_activity_idx` (`last_activity`)
						) ENGINE=MyISAM " . CHARSET . ";";
		$this->db->query($query);
	}

	private function ci_sessions_down()
	{
		$this->db->query('DROP TABLE IF EXISTS `ci_sessions`;');
	}
}

/* End of file 001_db.php */
/* Location: ./application/migrations/001_db.php */ 
