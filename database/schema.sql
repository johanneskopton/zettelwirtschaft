
--
-- Tabellenstruktur für Tabelle `connections`
--

CREATE TABLE `connections` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `origin_name` varchar(100) NOT NULL,
  `target_name` varchar(100) NOT NULL,
  `origin_user` varchar(100) NOT NULL,
  `target_user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zettel`
--

CREATE TABLE `zettel` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  `access` smallint(6) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `zettel`
--
ALTER TABLE `zettel`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `connections`
--
ALTER TABLE `connections`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT für Tabelle `zettel`
--
ALTER TABLE `zettel`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;