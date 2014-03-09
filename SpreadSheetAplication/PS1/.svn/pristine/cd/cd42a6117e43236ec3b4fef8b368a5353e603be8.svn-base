using FormulaEvaluator;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;

namespace TestFormulaEvaluator
{
    
    
    /// <summary>
    ///This is a test class for EvaluatorTest and is intended
    ///to contain all EvaluatorTest Unit Tests
    ///</summary>
    [TestClass()]
    public class EvaluatorTest
    {


        private TestContext testContextInstance;

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
        ///A test for Evaluate
        ///</summary>
        [TestMethod()]
        public void EvaluateTest()
        {
            string exp = string.Empty; // TODO: Initialize to an appropriate value
            Evaluator.Lookup variableEvaluator = null; // TODO: Initialize to an appropriate value
            int expected = 0; // TODO: Initialize to an appropriate value
            int actual;
            actual = Evaluator.Evaluate(exp, variableEvaluator);
            Assert.AreEqual(expected, actual);
            Assert.Inconclusive("Verify the correctness of this test method.");
        }

        /// <summary>
        ///A test for addSubBrack
        ///</summary>
        [TestMethod()]
        public void addSubBrackTest()
        {
            string a = string.Empty; // TODO: Initialize to an appropriate value
            Evaluator.addSubBrack(a);
            Assert.Inconclusive("A method that does not return a value cannot be verified.");
        }

        /// <summary>
        ///A test for bracketMulDiv
        ///</summary>
        [TestMethod()]
        public void bracketMulDivTest()
        {
            Evaluator.bracketMulDiv();
            Assert.Inconclusive("A method that does not return a value cannot be verified.");
        }

        /// <summary>
        ///A test for isInt
        ///</summary>
        [TestMethod()]
        [DeploymentItem("FormulaEvaluator.dll")]
        public void isIntTest()
        {
            int i = 0; // TODO: Initialize to an appropriate value
            Evaluator_Accessor.isInt(i);
            Assert.Inconclusive("A method that does not return a value cannot be verified.");
        }

        /// <summary>
        ///A test for isInt
        ///</summary>
        [TestMethod()]
        [DeploymentItem("FormulaEvaluator.dll")]
        public void isIntTest1()
        {
            string i = string.Empty; // TODO: Initialize to an appropriate value
            Evaluator_Accessor.isInt(i);
            Assert.Inconclusive("A method that does not return a value cannot be verified.");
        }
    }
}
