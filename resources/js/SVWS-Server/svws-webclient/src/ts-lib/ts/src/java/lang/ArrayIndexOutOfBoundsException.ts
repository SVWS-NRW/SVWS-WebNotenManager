import { IndexOutOfBoundsException } from './IndexOutOfBoundsException';

export class ArrayIndexOutOfBoundsException extends IndexOutOfBoundsException {

    public constructor(param? : String | Number) {
        super(typeof param === "undefined" ? "" : (typeof param === "number" ? "Array index out of range: " + param : param as String));
    }

	isTranspiledInstanceOf(name : string): boolean {
		return [
            'java.lang.ArrayIndexOutOfBoundsException',
            'java.lang.IndexOutOfBoundsException',
            'java.lang.RuntimeException',
            'java.lang.Exception',
            'java.lang.Throwable',
            'java.lang.Object',
            'java.lang.Serializable',
        ].includes(name);
	}

}

export function cast_java_lang_ArrayIndexOutOfBoundsException(obj : unknown) : ArrayIndexOutOfBoundsException {
	return obj as ArrayIndexOutOfBoundsException;
}