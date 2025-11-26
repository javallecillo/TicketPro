DELIMITER //
CREATE PROCEDURE SP_Deleteticketstatuse (
	IN _id INTEGER
) 
BEGIN

	DELETE FROM TicketStatuses WHERE id=_id;
END //