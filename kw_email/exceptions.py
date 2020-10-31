
class EmailException(Exception):
    """
     * Dead email exception - basic one when something does not work
    """

    def __init__(self, message: str = '', code: int = None, previous: Exception = None):
        self._message = message
        self._code = code
        self._previous = previous

    def get_message(self) -> str:
        return self._message

    def get_code(self) -> int:
        return self._code

    def get_previous(self) -> Exception:
        return self._previous
