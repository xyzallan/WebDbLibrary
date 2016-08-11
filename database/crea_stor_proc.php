

DROP PROCEDURE IF EXISTS `AddNewProj`//
CREATE DEFINER=`formis`@`localhost` PROCEDURE `AddNewProj`()
BEGIN
   insert into projektid (nimi, admin) values ('', 1);
END


DROP PROCEDURE `AddNewkats`//
CREATE DEFINER=`formis`@`localhost` PROCEDURE `AddNewKats`(IN TeemaID INT)
BEGIN
   insert into katsealad (id_projektid) values (TeemaID);
END

