using SS;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;
using SpreadsheetUtilities;

namespace SpreadSheetTest
{
    
    
    /// <summary>
    ///This is a test class for CellTest and is intended
    ///to contain all CellTest Unit Tests
    ///</summary>
    [TestClass()]
    public class CellTest
    {
        Cell_Accessor target;
        string name;
        object content;
        object value;

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
        ///A test for Cell Constructor
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void CellConstructorTest()
        {
            name = "A1"; 
            target = new Cell_Accessor(name);

            string T = target.getName();

            Assert.AreEqual(name, T);
        }



        /// <summary>
        ///A test for Cell Constructor
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void CellConstructorTest1()
        {
            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "A2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);

            Assert.AreEqual(target.getContent(), content);

            Assert.AreEqual(name, target.getName());

          // getContentTest();
           // getNameTest();
          //  getValueTest();
          //  setContTest();
        }



        /// <summary>
        ///A test for getContent
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void getContentTest()
        {

            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "A2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);


            object expected = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            object actual;
            actual = target.getContent();
            Assert.AreEqual(expected, actual);
            
        }

        /// <summary>
        ///A test for getName
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void getNameTest()
        {
            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "A2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);


            string expected = "A2"; // TODO: Initialize to an appropriate value
            string actual;
            actual = target.getName();
            Assert.AreEqual(expected, actual);
            
        }

        /// <summary>
        ///A test for getValue
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void getValueTest()
        {
            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "A2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);
            

            target.setValue("34");

            object expected = "34"; // TODO: Initialize to an appropriate value
            string actual;
            actual = target.getValue().ToString();
            Assert.AreEqual(expected, actual);

           
        }

        /// <summary>
        ///A test for parseMe
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void parseMeTest()
        {
            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);

            bool actual;
            actual = target.parseMe(target.getName());

            bool expected = true; // TODO: Initialize to an appropriate value
            
           
            Assert.AreEqual(expected, actual);
            
        }

        /// <summary>
        ///A test for setCont
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void setContTest()
        {

            content = new Formula("A1 + 2"); // TODO: Initialize to an appropriate value
            name = "A2"; // TODO: Initialize to an appropriate value
            target = new Cell_Accessor(content, name);
           
            object c = ""; // TODO: Initialize to an appropriate value
            target.setCont(c);

            Assert.AreEqual("", target.getContent());
        }

        /// <summary>
        ///A test for setValue
        ///</summary>
        [TestMethod()]
        [DeploymentItem("SpreadSheet.dll")]
        public void setValueTest()
        {
            //already tested
            Assert.IsTrue(true);
        }
    }
}
