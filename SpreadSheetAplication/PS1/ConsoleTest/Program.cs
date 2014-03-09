using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FormulaEvaluator;

namespace FormulaEvaluatorTester
{
    class Program
    {
        //public static Evaluator.Lookup eval = new Evaluator.Lookup(getInt);

        //static Evaluator.Lookup f = getInt;




        public static int getInt(String v)
        {
            if (v != null)
                return 1;

            else
                return 2;
        }
        public static Evaluator.Lookup variableEvaluator = Program.getInt;

        //public static Evaluator n;
        public static void Main(string[] args)
        {
            // Evaluator.Lookup eval = getInt;
            //new Evaluator.Lookup(eval)

            int a = Evaluator.Evaluate("2+2+A3", variableEvaluator);
            Console.Write(a);

            Console.Read();


        }









    }
    public class Test
    {
        public int getInt(String v)
        {
            if (v != null)
                return 1;

            else
                return 2;
        }
    }

}
