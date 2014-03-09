﻿using SpreadsheetUtilities;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;
using System.Collections.Generic;
using System.Linq.Expressions;
using Microsoft.JScript;        // needs a reference to Microsoft.JScript.dll
using Microsoft.JScript.Vsa;    // needs a reference to Microsoft.Vsa.dll

namespace PS3_Tester
{
    
    
    /// <summary>
    ///This is a test class for FormulaTest and is intended
    ///to contain all FormulaTest Unit Tests
    ///</summary>
    [TestClass()]
    public class FormulaTest
    {


        private TestContext testContextInstance;

        List<string> formula = new List<string>()
        { "23", "1/0","1(5*6)" , "", "1*@0", "(h1*2))+5)", ")4/2)", "65.345+I0("
        , "()", "*8", "1+*7","9 + 9.100+ h1 * j54 +h1 *g1" , "1+5*(4())", "((65)", "(2+2*(3/105))+(A1*1)", "2/3"};
        
        Formula target;
        Func<string, double> lookup = getInt;

        /// <summary>
        ///Gets or sets the test context which provides
        ///information about and functionality for the current test run.
        ///</summary>
        public TestContext TestContext
        {
            get
            {
                return testContextInstance;
            }
            set
            {
                testContextInstance = value;
            }
        }

        #region Additional test attributes
        // 
        //You can use the following additional attributes as you write your tests:
        //
        //Use ClassInitialize to run code before running the first test in the class
        //[ClassInitialize()]
        //public static void MyClassInitialize(TestContext testContext)
        //{
        //}
        //
        //Use ClassCleanup to run code after all tests in a class have run
        //[ClassCleanup()]
        //public static void MyClassCleanup()
        //{
        //}
        //
        //Use TestInitialize to run code before running each test
        //[TestInitialize()]
        //public void MyTestInitialize()
        //{
        //}
        //
        //Use TestCleanup to run code after each test has run
        //[TestCleanup()]
        //public void MyTestCleanup()
        //{
        //}
        //
        #endregion


        /// <summary>
        ///A test for Formula Constructor
        ///</summary>
        [TestMethod()]
        public void FormulaConstructorTest()
        {
             
            int errorC = 11;
            int actual = 0;
            foreach (string s in formula)
            {
                try
                {
                    target = new Formula(s);

                    if (target.Equals(null))
                        Assert.Fail();
                    else
                       // target.Evaluate(lookup);
                        Assert.IsTrue(target != null);
                }
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }
            }
            Assert.AreEqual(errorC, actual);

         
        }

        /// <summary>
        ///A test for Equals
        ///</summary>
        [TestMethod()]
        public void EqualsTest()
        {
           
            //string formula = string.Empty; // TODO: Initialize to an appropriate value
           // Formula target = new Formula(formula); // TODO: Initialize to an appropriate value
           // object obj = null; // TODO: Initialize to an appropriate value
            bool expected = false; // TODO: Initialize to an appropriate value
            bool actual;
            actual = new Formula("x1+2.0").Equals(new Formula_Accessor("x1+2.0"));
            Assert.AreEqual(expected, actual);
           
        }

                    //the delegate method
        /// <summary>
        /// Delegate method being passed to the Evaluate method returns the value of the variables
        /// </summary>
        /// <param name="v">string to be evaluated for value</param>
        /// <returns>Double value of 1</returns>
                public static double getInt(String v)
                {
                    if (v != null)
                        return 1;

                    else
                        return 2;
                }

       
    
 
        /// <summary>
        ///A test for Evaluate
        ///</summary>
        [TestMethod()]
        public void EvaluateTest()
        {

            int errorC = 12;//number or errors expected
            int actual = 0;//number sceen

            foreach (string s in formula)
            {
                try
                {
                    target = new Formula(s); // TODO: Initialize to an appropriate value
                    Formula target1 = new Formula(s);
                    object expected = target1.Evaluate(lookup).GetHashCode(); // TODO: Initialize to an appropriate value
                    object act;
                    act = target.Evaluate(lookup).GetHashCode();

                    Assert.AreEqual(expected, act);
                }
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }

                
            }
                //compares the number seen by the number present
            Assert.AreEqual(errorC, actual);
        }

        /// <summary>
        ///A test for GetHashCode
        ///</summary>
        [TestMethod()]
        public void GetHashCodeTest()
        {
            int errorC = 11;
            int actual = 0;

            foreach (string s in formula)
            {
                try
                {
                    
                        target = new Formula(s); // TODO: Initialize to an appropriate value
                        int expected = 0; // TODO: Initialize to an appropriate value
                        int act;
                        act = target.GetHashCode();
                        expected = target.GetHashCode();
                        Assert.AreEqual(expected, act);
                    
                }
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }

                
            }
            Assert.AreEqual(errorC, actual);
        }

        /// <summary>
        ///A test for GetTokens
        ///</summary>
        [TestMethod()]
        [DeploymentItem("Formula.dll")]
        public void GetTokensTest()
        {
            int errorC = 12;
            int actual = 0;

            foreach (string s in formula)
            {
                 try
                {
                    target = new Formula(s);
                    // TODO: Initialize to an appropriate value
                    object expected = target.Evaluate(lookup);
                    IEnumerable<string> act;
                    act = Formula_Accessor.GetTokens(s);
                    string f = null;
                    foreach (string S in act)
                    {
                        f = f + S;
                    }
                    Formula target1 = new Formula(f);

                    object ac = target1.Evaluate(lookup);
                    Assert.AreEqual(expected, ac);
                }
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }

                
            }
            Assert.AreEqual(errorC, actual);
            
            
        }

        /// <summary>
        ///A test for GetVariables
        ///</summary>
        [TestMethod()]
        public void GetVariablesTest()
        {
            int errorC = 13;
            int actual = 0;
            HashSet<string> duplicateVar = new HashSet<string>();

            foreach (string s in formula)
            {
                try
                {
                    target = new Formula(s);
                   
                    //counts the number of variables
                

                    foreach (string S in target.GetVariables())
                    {
                        if (Char.IsLetter(S[0]))
                        {
                            if (!duplicateVar.Add(S))
                                Assert.Fail("Duplicated Item in GetVariable()");
                        }
                        else
                            Assert.Fail("The Item recieved from GetVarible() is NOT a Variable");
                    }

                    if (duplicateVar.Count == 0)
                        Assert.Inconclusive("The test detected no Variables in your formula");
                }
           
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }

                
            }
           
            Assert.AreEqual(errorC, actual);
            
           
        }

        /// <summary>
        ///A test for ToString
        ///</summary>
        [TestMethod()]
        public void ToStringTest()
        {

            int errorC = 11;
            int actual = 0;

            foreach (string s in formula)
            {
                try
                {
                    target = new Formula(s); // TODO: Initialize to an appropriate value
                    string expected = target.getFormula(); // TODO: Initialize to an appropriate value
                    string act;
                    act = target.ToString();
                    Assert.AreEqual(expected, act);
                }
                catch (Exception e)
                {
                    Assert.AreNotEqual("", e);
                    actual++;
                    continue;
                }

            }

            Assert.AreEqual(errorC, actual);
        }

        /// <summary>
        ///A test for op_Equality
        ///</summary>
        [TestMethod()]
        public void op_EqualityTest()
        {
            Formula f1 = null; // TODO: Initialize to an appropriate value
            Formula f2 = null; // TODO: Initialize to an appropriate value
            bool expected = true; // TODO: Initialize to an appropriate value
            bool actual;
            actual = (f1 == f2);
            Assert.AreEqual(expected, actual);
            
        }

        /// <summary>
        ///A test for op_Inequality
        ///</summary>
        [TestMethod()]
        public void op_InequalityTest()
        {
            Formula f1 = null; // TODO: Initialize to an appropriate value
            Formula f2 = null; // TODO: Initialize to an appropriate value
            bool expected = false; // TODO: Initialize to an appropriate value
            bool actual;
            actual = (f1 != f2);
            Assert.AreEqual(expected, actual);
           
        }
    }
}
