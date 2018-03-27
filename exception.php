

<?


function exception_handler($e)
{
	var_dump(
		$e->getMessage(),
		get_class($e->getPrevious())
	);
}


function foo($bb)
{
	if (is_int($bb) === false){
			throw new InvalidArgumentException (" Please input only int values ") ;
	}
	return "the value was {$bb}";
}


try {
    var_dump(foo('1'));

} catch (Throwable $e) {
    var_dump(
        "<br> getMessage : ".$e->getMessage(), 
        "<br> getCode : ".$e->getCode(), 
        "<br> getFile :".$e->getFile(), 
        "<br> getLine : ".$e->getLine(), 
        $e->getTrace(), 
        "<br> getPrevious : ".$e->getPrevious(), 
        "<br> getTraceAsString : ".$e->getTraceAsString()
    );

    throw new Exception(
        '예외 체인을 따라 버블 업 합니다.' . $e->getMessage(),
        $e->getCode(),
        $e
    );
} finally {
    var_dump('<br><br>Finally 안쪽의 문장은 무조건 실행됩니다.');
}



?>