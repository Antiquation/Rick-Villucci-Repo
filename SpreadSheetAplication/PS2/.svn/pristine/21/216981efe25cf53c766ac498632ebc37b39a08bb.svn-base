using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Collections;

namespace console_test
{
    class Program
    {
        static void Main(string[] args)
        {
            SpreadsheetUtilities.DependencyGraph target = new SpreadsheetUtilities.DependencyGraph();
            target.AddDependency("a2", "a1");
            target.AddDependency("a3", "a1");
            target.AddDependency("b1", "a2");
            target.AddDependency("b2", "a2");
            target.GetDependents("b2");
            
        }

    }
}
