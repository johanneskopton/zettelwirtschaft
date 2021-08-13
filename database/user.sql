--
-- Tabellenstruktur für Tabelle `user`
--
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;