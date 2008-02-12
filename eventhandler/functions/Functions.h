#ifndef __FUNCTIONS__
#define __FUNCTIONS__

#include <mysql++/mysql++.h>
#include <time.h>
#include <math.h>
#include <map>
#include <sstream>

namespace functions //ToDo addslahes(std::string)
{
	/**
	* Liefet den Bonus durch die Temparatur 
	*
	* @param int t_min, int t_max Temparaturbegrenzung des Planeten
	* @author Glaubinix
	*/
	int getSolarPowerBonus(int t_min, int t_max);
	
	/**
	* Formatiert ein Datum 
	*
	* @param std::string vale zu formatierende Zahl
	* @author Glaubinix
	*/
	std::string format_time(std::time_t Zeitstempel=0);
	
	/**
	* Formatiert eine Zahl mit '
	*
	* @param std::string vale zu formatierende Zahl
	* @author Glaubinix
	*/
	std::string nf(std::string  value);
	
	/**
	* Speichert Daten in die Log-Tabelle
	*
	* @param int log_cat Log Kategorie
	* @param string log_text Log text
	* @param time_t log_timestamp Zeit
	* @author Glaubinix
	*/
	void add_log(mysqlpp::Connection* con_, int log_cat, std::string log_text, std::time_t log_timestamp=0);
	
	/**
	* Liefert eine Zahl mit Anzahl Komastellen
	*
	* @param float number zu bearbeitende Zahl
	* @param int precision Anzahl Nachkommastellen
	* @author Glaubinix
	*/
	double round(float number, int precision=0);
	
	/**
	* Liefert die Distance zwischen 2 Planeten
	*
	* @param mysqlpp::Connection* con_ Pointer zur MySQL Verbindung
	* @param int pid1 PlanetenID Planet 1
	* @param int pid1 PlanetenID Planet 2
	* @author Glaubinix
	*/
	double calcDistanceByPlanetId(mysqlpp::Connection* con_, int pid1, int pid2);
	
	/**
	* Liefert die Distance zwischen 2 Planeten
	*
	* @param mysqlpp::Row rowPlanet1 Planetendaten Planet 1
	* @param mysqlpp::Row rowPlanet2 Planetendaten Planet 2
	* @author Glaubinix
	*/
	double calcDistance(mysqlpp::Row rowPlanet1, mysqlpp::Row rowPlanet2);
	
}
#endif
