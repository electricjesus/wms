<?php
	require_once('Connections/dbGlobal.php');
	$q = "
		SELECT
			PRODUCTS.ID AS ProductID,
			PRODUCTS.Name AS ProductName,
			
			PRODUCTVARS.ID AS ProductVarID,
			PRODUCTVARS.VariantName AS VariantName,
			
			VARSIZES.ID AS VarSizeID,
			VARSIZES.*,
			(CAST(VARSIZES.Varsize AS CHAR) | CAST('axaa' AS CHAR)) as xVS
			FROM
				VARSIZES
			LEFT JOIN (
					PRODUCTS 
				RIGHT JOIN 
					PRODUCTVARS
				ON
					PRODUCTS.ID = PRODUCTVARS.ProductID
			)
			ON
				VARSIZES.PVarID = PRODUCTVARS.ID
		WHERE 1
		ORDER BY ProductName ASC, VariantName ASC, xVS ASC, PRODUCTS.ProdGroup;";
?>
