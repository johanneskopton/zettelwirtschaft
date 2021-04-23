
--
-- Tabellenstruktur für Tabelle `connections`
--

CREATE TABLE `connections` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `origin_name` varchar(100) NOT NULL,
  `target_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zettel`
--

CREATE TABLE `zettel` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `connections`
--
ALTER TABLE `connections`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `zettel`
--
ALTER TABLE `zettel`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;