delimiter $$

CREATE TABLE `sct_semantic_descriptions` (
  `DescriptionId` bigint(20) NOT NULL,
  `DescriptionType` int(11) DEFAULT NULL,
  `ConceptId` bigint(20) DEFAULT NULL,
  `LanguageCode` varchar(8) DEFAULT NULL,
  `InitialCapitalStatus` tinyint(1) DEFAULT NULL,
  `SemanticType` varchar(45) DEFAULT NULL,
  `Term` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`DescriptionId`),
  KEY `SemanticIndex` (`SemanticType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$

insert into sct_semantic_descriptions select DescriptionId,DescriptionType,ConceptId,LanguageCode,InitialCapitalStatus,"" as SemanticType, Term from sct_descriptions where DescriptionStatus=0$$
update sct_semantic_descriptions as d set SemanticType= 
(select concat("(",substring_index(FullySpecifiedName,"(",-1)) from sct_concepts as c where d.ConceptId=c.ConceptId)$$
