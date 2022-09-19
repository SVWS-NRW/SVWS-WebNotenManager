import { JavaObject, cast_java_lang_Object } from '../java/lang/JavaObject';
import { LogData, cast_de_nrw_schule_svws_logger_LogData } from '../logger/LogData';
import { Consumer, cast_java_util_function_Consumer } from '../java/util/function/Consumer';
import { JavaString, cast_java_lang_String } from '../java/lang/JavaString';
import { System, cast_java_lang_System } from '../java/lang/System';

export class LogConsumerConsole extends JavaObject implements Consumer<LogData> {

	public readonly printTime : boolean;

	public readonly printLevel : boolean;


	/**
	 * Erzeugt einen neuen Consumer für Log-Informationen, mit den Standardeinstellungen, 
	 * das weder Zeit noch Log-Level mit ausgegeben werden.
	 */
	public constructor();

	/**
	 * Erzeugt einen neuen Consumer für Log-Informationen.
	 * 
	 * @param printTime     gibt an, ob die Zeit beim Loggen ausgegeben wird oder nicht
	 * @param printLevel    gibt an, ob das Log-Level beim Loggen ausgegeben wird oder nicht
	 */
	public constructor(printTime : boolean, printLevel : boolean);

	/**
	 * Implementation for method overloads of 'constructor'
	 */
	public constructor(__param0? : boolean, __param1? : boolean) {
		super();
		if ((typeof __param0 === "undefined") && (typeof __param1 === "undefined")) {
			this.printTime = false;
			this.printLevel = false;
		} else if (((typeof __param0 !== "undefined") && typeof __param0 === "boolean") && ((typeof __param1 !== "undefined") && typeof __param1 === "boolean")) {
			let printTime : boolean = __param0 as boolean;
			let printLevel : boolean = __param1 as boolean;
			this.printTime = printTime;
			this.printLevel = printLevel;
		} else throw new Error('invalid method overload');
	}

	/**
	 * Diese Methode implementiert das funktionale Interface java.util.function.Consumer
	 * und gibt die empfangenen Log-Informationen auf der Kommandozeile aus.
	 * 
	 * @param t   die auszugebenden Log-Informationen 
	 */
	public accept(t : LogData) : void {
		if (t === null) 
			return;
		let s : String | null = (this.printTime ? t.getTime() + " " : "") + (this.printLevel ? t.getLevel() + " " : "") + t.getText().valueOf();
		if (t.isNewLine()) 
			console.log(JSON.stringify(s)); else 
			console.log(JSON.stringify(s));
	}

	isTranspiledInstanceOf(name : string): boolean {
		return ['de.nrw.schule.svws.logger.LogConsumerConsole', 'java.util.function.Consumer'].includes(name);
	}

}

export function cast_de_nrw_schule_svws_logger_LogConsumerConsole(obj : unknown) : LogConsumerConsole {
	return obj as LogConsumerConsole;
}
